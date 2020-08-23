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
                    'data' => User::whereRoleId(config('constant.roles.Haulers'))->orderBy("created_at", 'DESC')->get()
                        ], 200);
    }
    /**
     * create manager
     */
    public function createHauler(Request $request) {
        $validator = Validator::make($request->all(), [
                    'hauler_first_name' => 'required|string',
                    'hauler_last_name' => 'required|string',
                    'hauler_email' => 'required|string|email|unique:users',
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
                'prefix' => (isset($request->hauler_prefix) && $request->hauler_prefix != '' && $request->hauler_prefix != null) ? $request->hauler_prefix : null,
                'first_name' => $request->hauler_first_name,
                'last_name' => $request->hauler_last_name,
                'email' => $request->hauler_email,
                'phone' => $request->hauler_phone,
                'address' => $request->hauler_address,
                'city' => $request->hauler_city,
                'state' => $request->hauler_province,
                'zip_code' => $request->hauler_zipcode,
                'user_image' => (isset($request->hauler_image) && $request->hauler_image != '' && $request->hauler_image != null) ? $request->hauler_image : null,
                'role_id' => config('constant.roles.Haulers'),
                'created_from_id' => $request->user()->id,
                'is_confirmed' => 1,
                'is_active' => $request->hauler_is_active,
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
                    'hauler_email' => 'required|string|email|unique:users,email' . $request->customer_id,
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
            $haulerDetails = User::whereId($request->hauler_id)->first();
            if ($request->password != '' && $request->password != null) {
                $haulerDetails->password = bcrypt($request->password);
            }
            $haulerDetails->prefix = (isset($request->hauler_prefix) && $request->hauler_prefix != '' && $request->hauler_prefix != null) ? $request->hauler_prefix : null;
            $haulerDetails->first_name = $request->hauler_first_name;
            $haulerDetails->last_name = $request->hauler_last_name;
            $haulerDetails->email = $request->hauler_email;
            $haulerDetails->phone = $request->hauler_phone;
            $haulerDetails->address = $request->hauler_address;
            $haulerDetails->city = $request->hauler_city;
            $haulerDetails->state = $request->hauler_province;
            $haulerDetails->zip_code = $request->hauler_zipcode;
            $haulerDetails->user_image = (isset($request->hauler_image) && $request->hauler_image != '' && $request->hauler_image != null) ? $request->hauler_image : null;
            $haulerDetails->is_active = $request->hauler_is_active;
            $haulerDetails->save();
            DB::commit();
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler Customer details updated Successfully!',
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
                        'message' => 'Hauler customer deleted Successfully',
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
}