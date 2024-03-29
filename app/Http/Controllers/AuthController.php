<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use Socialite;
use Carbon\Carbon;
use GuzzleHttp\Client;
//use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\ChangePasswordMobile;
//use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
//use GuzzleHttp\Exception\GuzzleException;

class AuthController extends Controller
{
    public function signup(Request $request) {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users',
                    'password' => 'required|confirmed',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'zipcode' => 'required',
                    'role_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->role_id == config('constant.roles.Customer') || $request->role_id == config('constant.roles.haulers')) {
            try {
                $user = new User([
                    'prefix' => (isset($request->prefix) && $request->prefix != '' && $request->prefix != null) ? $request->prefix : null,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->province,
                    'zip_code' => $request->zipcode,
                    'user_image' => (isset($request->hauler_image) && $request->hauler_image != '' && $request->hauler_image != null) ? $request->hauler_image : null,
                    'role_id' => $request->role_id,
                    'is_active' => 1,
                    'password' => bcrypt($request->password),
                    'password_changed_at' => Carbon::now(),
                ]);
                if ($user->save()) {
                    $this->_welcomeEmail($user);
                    $this->_saveUserToHubSpot($user);
                }
                return response()->json([
                            'status' => true,
                            'message' => 'Your account was successfully created. We have sent you an e-mail to confirm your account.',
                            'data' => []
                                ], 200);
            } catch (\Exception $e) {
                Log::error(json_encode($e->getMessage()));
                return response()->json([
                            'status' => false,
                            'message' => json_encode($e->getMessage()),
                            'data' => []
                                ], 500);
            }
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Please check the type of user.',
                    'data' => []
                        ], 500);
    }
//    public function SocialSignup($provider, Request $request) {
//        $validator = Validator::make($request->all(), [
//                    'role_id' => 'required'
//        ]);
//        if ($validator->fails()) {
//            return response()->json([
//                        'status' => false,
//                        'message' => 'The given data was invalid.',
//                        'data' => $validator->errors()
//                            ], 422);
//        }
//        try {
//            $user = Socialite::driver($provider)->stateless()->user();
//            $checkIfExist = User::whereEmail($user->user['email'])->first();
//            if ($checkIfExist == null) {
//                if ($provider == config('constant.login_providers.google')) {
//                    $user = new User([
//                        'first_name' => $user->user['given_name'],
//                        'last_name' => $user->user['family_name'],
//                        'email' => $user->user['email'],
//                        'role_id' => $request->role_id,
//                        'is_active' => 1,
//                        'is_confirmed' => 1,
//                        'provider' => $provider,
//                        'token' => $user->token,
//                        'password_changed_at' => Carbon::now()
//                    ]);
//                } else if ($provider == config('constant.login_providers.facebook')) {
//                    $user = new User([
//                        'first_name' => $user['name'],
//                        'email' => $user['email'],
//                        'role_id' => $request->role_id,
//                        'is_active' => 1,
//                        'is_confirmed' => 1,
//                        'provider' => $provider,
//                        'token' => $user->token,
//                        'password_changed_at' => Carbon::now()
//                    ]);
//                }
//                $user->save();
//                $this->_welcomeEmail($user);
//                $this->_saveUserToHubSpot($user);
//            } else {
//                if ($checkIfExist->provider == null) {
//                    $checkIfExist->provider == $provider;
//                    $checkIfExist->token == $user->token;
//                }
//                if ($checkIfExist->password_changed_at == null || $checkIfExist->password_changed_at == '') {
//                    $checkIfExist->password_changed_at = Carbon::now();
//                }
//                $checkIfExist->save();
//                $user = $checkIfExist;
//            }
//            $tokenResult = $user->createToken('Personal Access Token');
//            $token = $tokenResult->token;
//            $token->save();
//            return response()->json([
//                        'status' => true,
//                        'message' => 'Login Successful',
//                        'data' => array(
//                            'access_token' => $tokenResult->accessToken,
//                            'token_type' => 'Bearer',
//                            'expires_at' => Carbon::parse(
//                                    $tokenResult->token->expires_at
//                            )->toDateTimeString(),
//                            'user' => $user
//                        )
//            ]);
//        } catch (\Exception $e) {
//            Log::error(json_encode($e->getMessage()));
//            return response()->json([
//                        'status' => false,
//                        'message' => $e->getMessage(),
//                        'data' => []
//                            ], 500);
//        }
//    }
    
    
    public function _welcomeEmail($user) {
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
     * save user to hubspot
     */
//    public function _saveUserToHubSpot($request) {
//        if ($request->role_id == config('constant.roles.Customer')) {
//            $userType = 'Customer';
//        } else {
//            $userType = 'Hauler';
//        }
//        $arr = [
//            'properties' => [
//                    [
//                    'property' => 'firstname',
//                    'value' => $request->first_name
//                ],
//                    [
//                    'property' => 'lastname',
//                    'value' => $request->last_name
//                ],
//                    [
//                    'property' => 'email',
//                    'value' => $request->email
//                ],
//                    [
//                    'property' => 'phone',
//                    'value' => $request->phone
//                ],
//                    [
//                    'property' => 'address',
//                    'value' => $request->address
//                ],
//                    [
//                    'property' => 'province',
//                    'value' => $request->province
//                ],
//                    [
//                    'property' => 'zipcode',
//                    'value' => $request->zipcode
//                ],
//                    [
//                    'property' => 'User type',
//                    'value' => $userType
//                ],
//            ]
//        ];
//        $post_json = json_encode($arr);
//        $endpoint = config('constant.hubspot.api_url') . env('HUBSPOT_API_KEY');
//        $client = new Client();
//        $res = $client->request('POST', $endpoint, [
//            'headers' => [
//                'Content-Type' => 'application/json'
//            ],
//            'body' => $post_json
//        ]);
//        return true;
//    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|string|email',
                    'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if ($user->is_confirmed == 0) {
                    return response()->json([
                                'status' => false,
                                'message' => 'Your account is not confirmed. Please click the confirmation link in your e-mail box.',
                                'data' => []
                                    ], 401);
                }
                $credentials = request(['email', 'password']);
                if (!Auth::attempt($credentials))
                    return response()->json([
                                'status' => false,
                                'message' => 'These credentials do not match our records.',
                                'data' => []
                                    ], 401);
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
            } else {
                return response()->json([
                            'status' => false,
                            'message' => 'No user with this email id',
                            'data' => []
                                ], 401);
            }
        } catch (\Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => json_encode($e->getMessage()),
                        'data' => []
                            ], 500);
        }
    }
    
    public function sendOtp(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if(User::where('email', $request->email)->exists()) {
            $otp = rand(100000,999999);
            $otpDetails = new ChangePasswordMobile(); 
            $otpDetails->email = $request->email;
            $otpDetails->otp = $otp;
            if($otpDetails->save()) {
                $data = [
                    'otp' => $otp
                ];
                Mail::send('email_templates.forgot_password_otp', $data, function ($message) use ($request, $otp) {
                        $message->to($request->email, $otp)->subject('Otp pin');
                        $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
                    });
                    return response()->json([
                        'status' => true,
                        'message' => 'Otp is sent on register email.',
                        'data' => [
                            'email' => $request->email
                        ]
            ], 200);
                    
            } else {
                return response()->json([
                        'status' => false,
                        'message' => 'Error while sending otp. Please try again later.',
                        'data' => []
                            ], 500);
            }
        }
        return response()->json([
                            'status' => false,
                            'message' => 'No user with this email id',
                            'data' => []
                                ], 401);
    }
    
    public function checkOtp(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required',
                    'otp' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }

        $checkOtp = ChangePasswordMobile::where('otp', $request->otp)->where('email', $request->email)->where('expired', 0)->latest()->first();
        if ($checkOtp) {
            if ($checkOtp->expired == 1) {
                return response()->json([
                            'status' => false,
                            'message' => 'Otp has expired.',
                            'data' => []
                                ], 401);
            } else {
                if ($checkOtp->otp == $request->otp) {
                    if (ChangePasswordMobile::whereId($checkOtp->id)->update(['expired' => 1])) {
                        return response()->json([
                                    'status' => true,
                                    'message' => 'otp matched.',
                                    'data' => [
                                        'email' => $request->email
                                    ]
                                        ], 200);
                    } else {
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Please try again later.',
                                    'data' => []
                                        ], 500);
                    }
                } else {
                    return response()->json([
                                'status' => false,
                                'message' => 'Wrong otp.',
                                'data' => []
                                    ], 401);
                }
            }
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unable to fetch the information',
                        'data' => []
                            ], 401);
        }
    }

    public function forgotPasswordMobile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required',
                    'password' => 'required|confirmed'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }

        if (User::where('email', $request->email)->update(['password' => bcrypt($request->password)])) {
            return response()->json([
                        'status' => true,
                        'message' => 'Password reset sucessfully.',
                        'data' => []
                            ], 200);
        }
        return response()->json([
                    'status' => true,
                    'message' => 'Please try again later.',
                    'data' => []
                        ], 500);
    }

    public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        try {
            $user = User::whereEmail($request->email)->first();
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
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    
    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'password' => 'required|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->password_changed_at = Carbon::now();
        $user->is_confirmed = 1;
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
    
    public function recoverPassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'password' => 'required|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        $user = User::whereEmail(base64_decode($request->hash_code))->first();
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
    
    public function confirmEmail(Request $request, $email) {
        $userEmail = base64_decode($email);
        $getUser = User::where('email', $userEmail)->first();
        if($getUser->email == $userEmail && $getUser->is_confirmed == 0) {
            $getUser->is_confirmed = 1;
            $getUser->save();
            $message = "Your email address has been successfully confirmed. Please login to proceed further.";
            $status = true;
            $errCode = 200;
        } else {
            $status = false;
            $message = "Your confirmation link has been expired.";
            $errCode = 400;
        }
        return redirect()->route('/');
    }
    
    public function confirmUpdateEmail(Request $request, $email, $id) {
        $userId = base64_decode($request->id);
        $userEmail = base64_decode($email);
        $getUser = User::whereId($userId)->first();
        if($getUser->email == $userEmail && $getUser->is_confirmed == 0) {
            $getUser->is_confirmed = 1;
            $getUser->save();
            $message = "Your email address has been successfully confirmed. Please login to proceed further.";
            $status = true;
            $errCode = 200;
        } else {
            $status = false;
            $message = "Your confirmation link has been expired.";
            $errCode = 400;
        }
        return redirect()->route('/');
    }
    
    public function user(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'User details!',
            'data' => $request->user()
        ]);
    }
    
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out',
            'data' => []
        ]);
    }

}
