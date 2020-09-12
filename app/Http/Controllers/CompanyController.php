<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use App\User;

class CompanyController extends Controller {
    /**
     * list of Hauler
     * return array()
     */
    public function listHauler() {
        return response()->json([
                    'status' => true,
                    'message' => 'Hauler List',
                    'data' => User::whereRoleId(config('constant.roles.Haulers'))->get()
                        ], 200);
    }
    
    public function listHaulerMobile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'offset' => 'required',
                    'take' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        return response()->json([
                    'status' => true,
                    'message' => 'Hauler List',
                    'data' => User::whereRoleId(config('constant.roles.Haulers'))->skip($request->offset)->take($request->take)->get()
                        ], 200);
    }
    /**
     * create manager
     */
    public function createHauler(Request $request) {
        $validator = Validator::make($request->all(), [
                    'hauler_first_name' => 'required|string',
                    'hauler_last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users',
                    'hauler_phone' => 'required',
                    'hauler_address' => 'required',
                    'hauler_city' => 'required',
                    'hauler_province' => 'required',
                    'hauler_zipcode' => 'required',
                    'hauler_is_active' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        try {
            DB::beginTransaction();
            $newPassword = Str::random();
            $user = new User([
                'prefix' => (isset($request->prefix) && $request->prefix != '' && $request->prefix != null) ? $request->prefix : null,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zipcode,
                'user_image' => (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null,
                'role_id' => config('constant.roles.Haulers'),
                'created_from_id' => $request->user()->id,
                'is_confirmed' => 1,
                'is_active' => $request->is_active,
                'password' => bcrypt($newPassword)
            ]);
            if ($user->save()) {
                $this->_confirmPassword($user, $newPassword);
                DB::commit();
                return response()->json([
                            'status' => true,
                            'message' => 'Successfully created Hauler!',
                            'data' => []
                                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    /**
     * get Hauler by id
     */
    public function getHauler(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Hauler details',
                    'data' => user::whereId($request->customer_id)->first()
                        ], 200);
    }
    /**
     * update manager
     */
    public function updateHauler(Request $request) {
        $validator = Validator::make($request->all(), [
                    'hauler_id' => 'required',
                    'hauler_first_name' => 'required|string',
                    'hauler_last_name' => 'required|string',
                    'hauler_phone' => 'required',
                    'hauler_address' => 'required',
                    'hauler_city' => 'required',
                    'hauler_province' => 'required',
                    'hauler_zipcode' => 'required',
                    'hauler_is_active' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $confirmed = 1;
        $haulerDetails = User::whereId($request->hauler_id)->first();
        if ($request->email != '' && $request->email != null) {
            $checkEmail = User::where('email', $request->email)->first();
            if ($checkEmail !== null) {
                if ($checkEmail->id !== $haulerDetails->id) {
                    return response()->json([
                                'status' => false,
                                'message' => 'Email is already taken.',
                                'data' => []
                                    ], 422);
                }
            }
            if ($haulerDetails->email !== $request->email) {
                $confirmed = 0;
            }
        }
        try {
            DB::beginTransaction();
            
            if ($request->password != '' && $request->password != null) {
                $haulerDetails->password = bcrypt($request->password);
            }
            $haulerDetails->prefix = (isset($request->hauler_prefix) && $request->hauler_prefix != '' && $request->hauler_prefix != null) ? $request->hauler_prefix : null;
            $haulerDetails->first_name = $request->hauler_first_name;
            $haulerDetails->last_name = $request->hauler_last_name;
            $haulerDetails->email = $request->email;
            $haulerDetails->phone = $request->hauler_phone;
            $haulerDetails->address = $request->hauler_address;
            $haulerDetails->city = $request->hauler_city;
            $haulerDetails->state = $request->hauler_province;
            $haulerDetails->zip_code = $request->hauler_zipcode;
            $haulerDetails->user_image = (isset($request->hauler_image) && $request->hauler_image != '' && $request->hauler_image != null) ? $request->hauler_image : null;
            $haulerDetails->is_active = $request->hauler_is_active;
            $haulerDetails->is_confirmed = $confirmed;
            $haulerDetails->save();
            DB::commit();
            if ($confirmed == 0) {
                    $this->_updateEmail($haulerDetails, $request->email);
                    $request->user()->token()->revoke();
                    return response()->json([
                                'status' => true,
                                'message' => 'Successfully logged out',
                                'data' => []
                    ]);
                }
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler details updated Successfully!',
                        'data' => []
                            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    /**
     * delete hauler
     */
    public function deleteHauler(Request $request) {
        try {
            User::whereId($request->customer_id)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler deleted Successfully',
                        'data' => []
                            ], 200);
        } catch (\Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    /**
     * email for new registration and password
     */
    public function _confirmPassword($user, $newPassword) {
        $name = $user->first_name . ' ' . $user->last_name;
        $data = array(
            'user' => $user,
            'password' => $newPassword
        );
        Mail::send('email_templates.welcome_email_manager', $data, function ($message) use ($user, $name) {
            $message->to($user->email, $name)->subject('Login Details');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
    }
    
    public function _updateEmail($user, $email) {
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
}