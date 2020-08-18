<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Socialite;
use Mail;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\User;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] first_name
     * @param  [string] last_name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'role_id' => 'required',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            //make array for hubspot

            if ($request->user_image != '' && $request->user_image != null) {
                //upload path
                $folderPath = "images/";
                //get base64 image
                $img = $request->user_image;
                //decode base64
                $image_parts = explode(";base64,", $img);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $file = $folderPath . uniqid() . '. ' . $image_type;

                //check if directory exist if not create one
                $path = public_path() . '/images';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                //upload image
                file_put_contents($file, $image_base64);
            } else {
                $file = null;
            }

            //create new user
            $user = new User([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'is_active' => 1,
                'phone' => $request->phone,
                'user_image' => $file,
                'password_changed_at' => Carbon::now(),
                'password' => bcrypt($request->password)
            ]);

            if ($user->save()) {
                $this->_welcomeEmail($user);
                $this->_saveUserToHubSpot($user);
            }

            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Your account was successfully created. We have sent you an e-mail to confirm your account.',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    /**
     * save user to hubspot
     */
    public function _saveUserToHubSpot($request)
    {
        $arr = [
            'properties' => [
                [
                    'property' => 'firstname',
                    'value' => $request->first_name
                ],
                [
                    'property' => 'lastname',
                    'value' => $request->last_name
                ],
                [
                    'property' => 'email',
                    'value' => $request->email
                ],
            ]
        ];
        $post_json = json_encode($arr);
        $endpoint = config('constant.hubspot.api_url') . env('HUBSPOT_API_KEY');
        $client = new Client();
        $res = $client->request('POST', $endpoint, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => $post_json
        ]);

        return true;
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials))
                return response()->json([
                    'status' => false,
                    'message' => 'These credentials do not match our records.',
                    'data' => []
                ], 401);
            $user = $request->user();

            //check if account is not confirmed
            if ($user->is_confirmed == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is not confirmed. Please click the confirmation link in your e-mail box.',
                    'data' => []
                ], 401);
            }

            //restrict user with user type
            if ($request->requestType == 1 && ($user->role_id == 4 || $user->role_id == 5 || $user->role_id == 5 || $user->role_id == 6)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not Authorized',
                    'data' => []
                ], 401);

            } else if ($request->requestType == 2 && ($user->role_id == 1 || $user->role_id == 2 || $user->role_id == 3)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not Authorized',
                    'data' => []
                ], 401);
            }

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            // if ($request->remember_me)
            //     $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            return response()->json([
                'status' => true,
                'message' => 'Login Successful',
                'data' => array(
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString(),
                    'user' => $user
                )
            ]);
        } catch (\Exception $e) {
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out',
            'data' => []
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'User details!',
            'data' => $request->user()
        ]);
    }

    /**
     * Edit Admin Profile
     */
    public function editProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $request->user()->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            $loggedInUser = $request->user();

            Log::info($request->user_image);
            $loggedInUser->first_name = $request->first_name;
            $loggedInUser->last_name = $request->last_name;
            // $loggedInUser->email = $request->email;
            $loggedInUser->phone = $request->phone;
            $loggedInUser->user_image = $request->user_image;

            $loggedInUser->save();
            $checkemail =  User::where('email', $request->email)->first();
            if (empty($checkemail)) {
                $this->_updateEmail($loggedInUser, $request->email);
                $msg = "User Profile edit successfully. We have sent you an e-mail to confirm your email address.'";
            } else {
                $msg = "User Profile edit successfully.";
            }

            Log::info($loggedInUser);
            //return success response
            return response()->json([
                'status' => true,
                'message' => $msg,
                'data' => $loggedInUser
            ]);
        } catch (\Exception $e) {
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    /**
     * email for new account email confirmation
     */
    public function _welcomeEmail($user)
    {
        $name = $user->first_name . ' ' . $user->last_name;
        $data = array(
            'name' => $name,
            'email' => $user->email,
            'verificationLink' => env('APP_URL') . 'confirm-email/' . base64_encode($user->email)
        );

        Mail::send('email_templates.welcome_email', $data, function ($message) use ($user, $name) {
            $message->to($user->email, $name)->subject('Email Confirmation');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
    }

    /**
     * email for email address confirmation
     */
    public function _updateEmail($user, $email)
    {
        $name = $user->first_name . ' ' . $user->last_name;
        $data = array(
            'name' => $name,
            'email' => $email,
            'verificationLink' => env('APP_URL') . 'confirm-update-email/' . base64_encode($email) . '/' . base64_encode($user->id)
        );

        Mail::send('email_templates.welcome_email', $data, function ($message) use ($name, $email) {
            $message->to($email, $name)->subject('Email Address Confirmation');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
    }


    public function confirmEmail(Request $request)
    {
        $email = base64_decode($request->decode_code);

        $getUser = User::whereEmail($email)->first();
        //check if email exist
        if ($getUser != null) {
            $getUser->is_confirmed = 1;
            $getUser->save();
            $message = "Your account has been successfully confirmed. Please login to proceed further.";
            $status = true;
            $errCode = 200;
        } else {
            $status = false;
            $message = "Your confirmation link has been expired.";
            $errCode = 400;
        }

        // return response()->json([
        //     'status' => $status,
        //     'message' => $message,
        //     'data' => []
        // ], $errCode);

        // return redirect()->route('test.index');

        return redirect()->route('/');

        // return Redirect::back();
    }

    /**
     * social login
     */
    public function SocialSignup($provider)
    {
        try {
            // Socialite will pick response data automatic 
            $user = Socialite::driver($provider)->stateless()->user();

            $checkIfExist = User::whereEmail($user->user['email'])->first();

            if ($checkIfExist == null) {

                if ($provider == config('constant.login_providers.google')) {
                    //create new user
                    $user = new User([
                        'first_name' => $user->user['given_name'],
                        'last_name' => $user->user['family_name'],
                        'email' => $user->user['email'],
                        'role_id' => 4,
                        'is_active' => 1,
                        'is_confirmed' => 1,
                        'provider' => $provider,
                        'token' => $user->token,
                        'password_changed_at' => Carbon::now()
                    ]);
                } else if ($provider == config('constant.login_providers.facebook')) {
                    //create new user
                    $user = new User([
                        'first_name' => $user['name'],
                        'email' => $user['email'],
                        'role_id' => 4,
                        'is_active' => 1,
                        'is_confirmed' => 1,
                        'provider' => $provider,
                        'token' => $user->token,
                        'password_changed_at' => Carbon::now()
                    ]);
                }

                $user->save();
            } else {
                if ($checkIfExist->provider == null) {
                    //save provider and token if not saved earier or if any existing account now login with social account
                    $checkIfExist->provider == $provider;
                    $checkIfExist->token == $user->token;
                }

                if ($checkIfExist->password_changed_at == null || $checkIfExist->password_changed_at == '') {
                    $checkIfExist->password_changed_at = Carbon::now();
                }

                $checkIfExist->save();

                $user = $checkIfExist;
            }
            //process token
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;

            $token->save();
            return response()->json([
                'status' => true,
                'message' => 'Login Successful',
                'data' => array(
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString(),
                    'user' => $user
                )
            ]);
        } catch (\Exception $e) {
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    /**
     * social login
     */
    public function changePassword(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $user->password = bcrypt($request->password);
        $user->password_changed_at = Carbon::now();

        if ($user->save()) {
            $message = "Password changed successfully.";
            $status = true;
            $errCode = 200;
        } else {
            $status = false;
            $message = "Something went wrong.";
            $errCode = 400;
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => []
        ], $errCode);
    }

    public function confirmUpdateEmail(Request $request)
    {

        $id = base64_decode($request->id);
        $email = base64_decode($request->email);

        $getUser = User::whereId($id)->first();
        //check if email exist
        if ($getUser != null) {
            $getUser->email = $email;
            $getUser->save();
            $message = "Your email address has been successfully confirmed. Please login to proceed further.";
            $status = true;
            $errCode = 200;
        } else {
            $status = false;
            $message = "Your confirmation link has been expired.";
            $errCode = 400;
        }

        // return response()->json([
        //     'status' => $status,
        //     'message' => $message,
        //     'data' => []
        // ], $errCode);

        // return redirect()->route('test.index');

        return redirect()->route('/');

        // return Redirect::back();
    }

    /**
     * forgot password
     */
    public function forgotPassword(Request $request)
    {

        try {
            $user = User::whereEmail($request->email)->first();
            //return with error if email not found
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not found!',
                    'data' => []
                ], 404);
            }

            $name = $user->first_name . ' ' . $user->last_name;
            $data = array(
                'name' => $name,
                'email' => $user->email,
                'verificationLink' => env('APP_URL') . 'change-password/' . base64_encode($user->email)
            );

            $sendForGotEmail = Mail::send('email_templates.forgot_password', $data, function ($message) use ($user, $name) {
                $message->to($user->email, $name)->subject('Email Confirmation');
                $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
            });

            return response()->json([
                'status' => true,
                'message' => 'We have sent you a email for change password link. Please check and proceed further.',
                'data' => []
            ]);
        } catch (\Exception $e) {
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    /**
     * recover password
     */
    public function recoverPassword(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        $user = User::whereEmail(base64_decode($request->hash_code))->first();
        //return with error if email not found
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Link Expired!',
                'data' => []
            ], 422);
        } else {
            $user->password = bcrypt($request->password);
            $user->password_changed_at = Carbon::now();

            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully',
                'data' => []
            ], 200);
        }
    }
}
