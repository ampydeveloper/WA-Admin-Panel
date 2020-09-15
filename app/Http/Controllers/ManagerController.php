<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use Validator;
use Carbon\Carbon;
use App\ManagerDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller {

    public function createAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
                    'admin_first_name' => 'required',
                    'admin_last_name' => 'required',
                    'email' => 'required|email|unique:users',
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
                'first_name' => $request->admin_first_name,
                'last_name' => $request->admin_last_name,
                'email' => $request->email,
                'role_id' => config('constant.roles.Admin'),
                'created_from_id' => $request->user()->id,
                'is_confirmed' => 1,
                'is_active' => 1,
                'password' => bcrypt($newPassword)
            ]);
            if ($user->save()) {
                $this->_confirmPassword($user, $newPassword);
                DB::commit();
                return response()->json([
                            'status' => true,
                            'message' => 'Admin created successfully.',
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
    
    public function editAdminProfile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'admin_id' => 'required',
                    'admin_first_name' => 'required|string',
                    'admin_last_name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $confirmed = 1;
        $admin = User::whereId($request->admin_id)->first();
        if ($request->email != '' && $request->email != null) {
            $checkEmail = User::where('email', $request->email)->first();
            if ($checkEmail !== null) {
                if ($checkEmail->id !== $admin->id) {
                    return response()->json([
                                'status' => false,
                                'message' => 'Email is already taken.',
                                'data' => []
                                    ], 422);
                }
            } 
            if ($admin->email !== $request->email) {
                $confirmed = 0;
            }
        }
        try {
            if ($request->password != '' && $request->password != null) {
                $admin->password = bcrypt($request->password);
            }
            $admin->first_name = $request->admin_first_name;
            $admin->last_name = $request->admin_last_name;
            $admin->email = $request->email;
            $admin->is_confirmed = $confirmed;
            $admin->save();
            if ($confirmed == 0) {
                $this->_updateEmail($admin, $request->email);
                if ($request->user()->id == $request->admin_id) {
                    $request->user()->token()->revoke();
                    return response()->json([
                                'status' => true,
                                'message' => 'Successfully logged out',
                                'data' => []
                    ]);
                }
            }
            return response()->json([
                        'status' => true,
                        'message' => 'Admin updated sucessfully.',
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

    /**
     * create manager
     */
    public function createManager(Request $request) {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'manager_phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'manager_zipcode' => 'required',
//                    'card_image' => 'required',
                    'id_photo' => 'required',
                    'salary' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        if($request->user()->role_id == config('constant.roles.Admin')) {
        } else {
        }
        try {
            DB::beginTransaction();
            $newPassword = Str::random();
            $user = new User([
                'prefix' => (isset($request->prefix) && $request->prefix != '' && $request->prefix != null) ? $request->prefix : null,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->manager_phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->province,
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
                    'identification_number' => $request->identification_number,
                    'document' => $request->id_photo,
                    'salary' => $request->salary,
                    'joining_date' => date('Y/m/d'),
                ]);
                if ($managerDetails->save()) {
                    $this->_confirmPassword($user, $newPassword);
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Successfully created manager.',
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
    public function updateManager(Request $request) {
        $validator = Validator::make($request->all(), [
                    'manager_id' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'zipcode' => 'required',
                    'is_active' => 'required',
//                    'card_image' => 'required',
                    'id_photo' => 'required',
                    'salary' => 'required',
                    'joining_date' => 'required',
             'releaving_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $confirmed = 1;
        $manager = User::whereId($request->manager_id)->first();
        if ($request->email != '' && $request->email != null) {
            $checkEmail = User::where('email', $request->email)->first();
            if ($checkEmail !== null) {
                if ($checkEmail->id !== $manager->id) {
                    return response()->json([
                                'status' => false,
                                'message' => 'Email is already taken.',
                                'data' => []
                                    ], 422);
                }
            }
            if ($manager->email !== $request->email) {
                $confirmed = 0;
            }
        }
        try {
            DB::beginTransaction();
            if ($request->password != '' && $request->password != null) {
                $manager->password = bcrypt($request->password);
            }
            $manager->prefix = (isset($request->prefix) && $request->prefix != '' && $request->prefix != null) ? $request->prefix : null;
            $manager->first_name = $request->first_name;
            $manager->last_name = $request->last_name;
            $manager->email = $request->email;
            $manager->phone = $request->phone;
            $manager->address = $request->address;
            $manager->city = $request->city;
            $manager->state = $request->province;
            $manager->zip_code = $request->zipcode;
            $manager->user_image = (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null;
            $manager->is_active = $request->is_active;
            $manager->is_confirmed = $confirmed;
            if ($manager->save()) {
                if ($manager->role_id != config('constant.roles.Admin')) {
                    ManagerDetail::whereUserId($request->manager_id)->update([
                        'salary' => $request->salary,
                        'identification_number' => $request->identification_number,
                        'joining_date' => $request->joining_date,
                        'releaving_date' => isset($request->releaving_date) ? $request->releaving_date : null,
                        'document' => $request->id_photo,
                    ]);
                }
                DB::commit();
                if ($confirmed == 0) {
                    $this->_updateEmail($manager, $request->email);
                    if ($request->user()->id == $request->manager_id) {
                        $request->user()->token()->revoke();
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Successfully logged out',
                                    'data' => []
                        ]);
                    }
                }
                return response()->json([
                            'status' => true,
                            'message' => 'Manager details updated successfully.',
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
    public function getManager(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Manager Details',
                    'data' => ManagerDetail::whereUserId($request->manager_id)->with('user')->first()
                        ], 200);
    }
    /**
     * get admin details
     */
    public function getAdmin(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Admin Details',
                    'data' => User::whereId($request->admin_id)->first()
                        ], 200);
    }
    /**
     * delete manager
     */
    public function deleteManager(Request $request) {
        try {
            User::whereId($request->manager_id)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'Manager deleted successfully.',
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
                    'data' => User::whereRoleId(config('constant.roles.Admin_Manager'))->with('managerDetails')->get()
                        ], 200);
    }
    
    public function listManagerMobile(Request $request) {
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
                    'message' => 'Admin List',
                    'data' => User::whereRoleId(config('constant.roles.Admin_Manager'))->with('managerDetails')->skip($request->offset)->take($request->take)->get()
                        ], 200);
    }
    /**
     * list admin
     */
    public function listAdmin() {
        return response()->json([
                    'status' => true,
                    'message' => 'Admin List',
                    'data' => User::whereRoleId(config('constant.roles.Admin'))->get()
                        ], 200);
    }
    
    public function listAdminMobile(Request $request) {
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
                    'message' => 'Admin List',
                    'data' => User::whereRoleId(config('constant.roles.Admin'))->skip($request->offset)->take($request->take)->get()
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