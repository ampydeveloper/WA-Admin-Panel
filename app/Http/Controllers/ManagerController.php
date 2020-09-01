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
use App\ManagerDetail;

class ManagerController extends Controller {

    public function createAdmin(Request $request) {
//        dd($request->admin_email);
        $validator = Validator::make($request->all(), [
                    'admin_first_name' => 'required',
                    'admin_last_name' => 'required',
                    'email' => 'required|email|unique:users',
        ]);
//        dd($validator);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
//            dd('in');
        try {
            DB::beginTransaction();
            $newPassword = Str::random();
            $user = new User([
                'first_name' => $request->admin_first_name,
                'last_name' => $request->admin_last_name,
                'email' => $request->email,
                'role_id' => config('constant.roles.Admin'),
                'created_from_id' => $request->user()->id,
                'is_confirmed' => 1,
                'is_active' => 1,
                'password' => bcrypt($newPassword)
            ]);
//            dd($user);
            if ($user->save()) {
//                $this->_confirmPassword($user, $newPassword);
                DB::commit();
                return response()->json([
                            'status' => true,
                            'message' => 'Successfully created Admin!',
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
     * create manager
     */
    public function createManager(Request $request) {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
                    'manager_first_name' => 'required',
                    'manager_last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'manager_phone' => 'required',
                    'manager_address' => 'required',
                    'manager_city' => 'required',
                    'manager_province' => 'required',
                    'manager_zipcode' => 'required',
                    'manager_card_image' => 'required',
                    'manager_id_card' => 'required',
                    'salary' => 'required',
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
                'prefix' => (isset($request->manager_prefix) && $request->manager_prefix != '' && $request->manager_prefix != null) ? $request->manager_prefix : null,
                'first_name' => $request->manager_first_name,
                'last_name' => $request->manager_last_name,
                'email' => $request->email,
                'phone' => $request->manager_phone,
                'address' => $request->manager_address,
                'city' => $request->manager_city,
                'state' => $request->manager_province,
                'zip_code' => $request->manager_zipcode,
                'user_image' => (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null,
                'role_id' => config('constant.roles.Admin_Manager'),
                'created_from_id' => $request->user()->id,
                'is_confirmed' => 1,
                'is_active' => 1,
                'password' => bcrypt($newPassword)
            ]);
            if ($user->save()) {
                $managerDetails = new ManagerDetail([
                    'user_id' => $user->id,
                    'identification_number' => $request->manager_id_card,
                    'document' => $request->manager_card_image,
                    'salary' => $request->salary,
                    'joining_date' => date('Y/m/d'),
                ]);
                if ($managerDetails->save()) {
//                    $this->_confirmPassword($user, $newPassword);
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Successfully created Manager!',
                                'data' => []
                                    ], 200);
                }
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
     * update manager
     */
    public function updateManager(Request $request, $manager_id) {
        $validator = Validator::make($request->all(), [
                    'manager_first_name' => 'required',
                    'manager_last_name' => 'required',
                    'manager_phone' => 'required',
                    'manager_address' => 'required',
                    'manager_city' => 'required',
                    'manager_province' => 'required',
                    'manager_zipcode' => 'required',
                    'manager_is_active' => 'required',
                    'manager_card_image' => 'required',
                    'manager_id_card' => 'required',
                    'salary' => 'required',
                    'joining_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
            $manager = User::whereId($request->manager_id)->first();
            if ($request->email != '' && $request->email != null) {
                $checkEmail = User::where('email', $request->email)->first();
                if($checkEmail !== null) {
                    if($checkEmail->id !== $manager->id) {
                        return response()->json([
                        'status' => false,
                        'message' => 'Email is already taken.',
                        'data' => []
                            ], 422);
                    }
                    
                }
            }
        try {
            DB::beginTransaction();
            if ($request->password != '' && $request->password != null) {
                $manager->password = bcrypt($request->password);
            }
            $manager->prefix = (isset($request->manager_prefix) && $request->manager_prefix != '' && $request->manager_prefix != null) ? $request->manager_prefix : null;
            $manager->first_name = $request->manager_first_name;
            $manager->last_name = $request->manager_last_name;
            $manager->email = $request->email;
            $manager->phone = $request->manager_phone;
            $manager->address = $request->manager_address;
            $manager->city = $request->manager_city;
            $manager->state = $request->manager_province;
            $manager->zip_code = $request->manager_zipcode;
            $manager->user_image = (isset($request->manager_image) && $request->manager_image != '' && $request->manager_image != null) ? $request->manager_image : null;
            $manager->is_active = $request->manager_is_active;
            if ($manager->save()) {
                if ($manager->role_id != config('constant.roles.Admin')) {
                    ManagerDetail::whereUserId($request->manager_id)->update([
                        'salary' => $request->salary,
                        'identification_number' => $request->manager_id_card,
                        'joining_date' => $request->joining_date,
                        'releaving_date' => isset($request->releaving_date) ? $request->releaving_date : null,
                        'document' => $request->manager_card_image,
                    ]);
                }
                DB::commit();
                return response()->json([
                            'status' => true,
                            'message' => 'Manager details updated Successfully!',
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
     * get manager details
     */
    public function getManager(Request $request, $manager_id) {
        return response()->json([
                    'status' => true,
                    'message' => 'Manager Details',
                    'data' => ManagerDetail::whereUserId($manager_id)->with('user')->first()
                        ], 200);
    }
    /**
     * get admin details
     */
    public function getAdmin(Request $request, $adminId) {
        return response()->json([
                    'status' => true,
                    'message' => 'Admin Details',
                    'data' => User::whereId($adminId)->first()
                        ], 200);
    }
    /**
     * delete manager
     */
    public function deleteManager(Request $request, $managerId) {
        try {
            User::whereId($managerId)->delete();

            return response()->json([
                        'status' => true,
                        'message' => 'User deleted Successfully',
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
     * list manager
     */
    public function listManager() {
        return response()->json([
                    'status' => true,
                    'message' => 'Manager List',
                    'data' => User::whereRoleId(config('constant.roles.Admin_Manager'))->with('managerDetails')->orderBy("created_at", 'DESC')->get()
                        ], 200);
    }
    /**
     * list admin
     */
    public function listAdmin() {
        return response()->json([
                    'status' => true,
                    'message' => 'Admin List',
                    'data' => User::whereRoleId(config('constant.roles.Admin'))->orderBy("created_at", 'DESC')->get()
                        ], 200);
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