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
use App\CustomerActivity;

class CompanyController extends Controller {
    
    public function listHauler(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler List',
                        'data' => User::whereRoleId(config('constant.roles.Haulers'))->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function listHaulerMobile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'offset' => 'required',
                    'take' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler List',
                        'data' => User::whereRoleId(config('constant.roles.Haulers'))->skip($request->offset)->take($request->take)->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function createHauler(Request $request) {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'zipcode' => 'required',
                    'is_active' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }

        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
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
                    'state' => $request->province,
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
                    $customerActivity = new CustomerActivity([
                        'customer_id' => $user->id,
                        'created_by' => $request->user()->id,
                        'activities' => 'Customer is created from wellington office by ' . $request->user()->first_name,
                    ]);
                    if ($customerActivity->save()) {
                        // Notification is required.
                        DB::commit();
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Successfully created hauler.',
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getHauler(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler details',
                        'data' => user::whereId($request->customer_id)->first()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function updateHauler(Request $request) {
        $validator = Validator::make($request->all(), [
                    'hauler_id' => 'required',
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'zipcode' => 'required',
                    'is_active' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $haulerDetails = User::whereId($request->hauler_id)->first();
            if ($request->email != '' && $request->email != null) {
                if ($haulerDetails->email !== $request->email) {
                    $confirmed = 0;
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
                }
            }
            try {
                DB::beginTransaction();

                if ($request->password != '' && $request->password != null) {
                    $haulerDetails->password = bcrypt($request->password);
                }
                $haulerDetails->prefix = (isset($request->prefix) && $request->prefix != '' && $request->prefix != null) ? $request->prefix : null;
                $haulerDetails->first_name = $request->first_name;
                $haulerDetails->last_name = $request->last_name;
                $haulerDetails->email = $request->email;
                $haulerDetails->phone = $request->phone;
                $haulerDetails->address = $request->address;
                $haulerDetails->city = $request->city;
                $haulerDetails->state = $request->province;
                $haulerDetails->zip_code = $request->zipcode;
                $haulerDetails->user_image = (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null;
                $haulerDetails->is_active = $request->is_active;
                if (isset($confirmed)) {
                    $haulerDetails->is_confirmed = $confirmed;
                }
                $haulerDetails->save();
                DB::commit();
                if (isset($confirmed)) {
                    $this->_updateEmail($haulerDetails, $request->email);
                }
                return response()->json([
                            'status' => true,
                            'message' => 'Hauler details updated successfully.',
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function deleteHauler(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            try {
                DB::beginTransaction();
                if(User::whereId($request->customer_id)->delete()) {
                    $customerActivity = new CustomerActivity([
                        'customer_id' => $request->customer_id,
                        'created_by' => $request->user()->id,
                        'activities' => 'Customer is deleted from wellington office by ' . $request->user()->first_name,
                    ]);
                    if ($customerActivity->save()) {
                        // Notification is required.
                        DB::commit();
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Hauler deleted successfully.',
                                    'data' => []
                                        ], 200);
                    }
                }
            } catch (\Exception $e) {
                Log::error(json_encode($e->getMessage()));
                return response()->json([
                            'status' => false,
                            'message' => $e->getMessage(),
                            'data' => []
                                ], 500);
            }
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
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
    
    public function listHaulerDriver(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler drivers list',
                        'data' => User::whereRoleId(config('constant.roles.Hauler_driver'))->where('created_by', $request->hauler_id)->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function listHaulerMobileDriver(Request $request) {
        $validator = Validator::make($request->all(), [
                    'hauler_id' => 'required',
                    'offset' => 'required',
                    'take' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler drivers list',
                        'data' => User::whereRoleId(config('constant.roles.Hauler_driver'))->where('created_by', $request->hauler_id)->skip($request->offset)->take($request->take)->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function createHaulerDriver(Request $request) {
        $validator = Validator::make($request->all(), [
                    'hauler_id' => 'required',
                    'driver_first_name' => 'required',
                    'driver_last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'driver_phone' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }

        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            try {
                $newPassword = Str::random();
                $user = new User([
                    'prefix' => (isset($request->driver_prefix) && $request->driver_prefix != '' && $request->driver_prefix != null) ? $request->driver_prefix : null,
                    'first_name' => $request->driver_first_name,
                    'last_name' => $request->driver_last_name,
                    'email' => $request->email,
                    'phone' => $request->driver_phone,
                    'user_image' => (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null,
                    'role_id' => config('constant.roles.Hauler_driver'),
                    'created_from_id' => $request->user()->id,
                    'created_by' => $request->hauler_id,
                    'is_confirmed' => 1,
                    'is_active' => 1,
                    'password' => bcrypt($newPassword)
                ]);
                if ($user->save()) {
                    $this->_confirmPassword($user, $newPassword);
                    $customerActivity = new CustomerActivity([
                        'customer_id' => $user->id,
                        'created_by' => $request->user()->id,
                        'activities' => $user->first_name . ' is added as driver by wellington office by ' . $request->user()->first_name,
                    ]);
                    if ($customerActivity->save()) {
                        // Notification is required.
                        DB::commit();
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Driver created successfully.',
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getHaulerDriver(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Hauler details',
                        'data' => user::whereId($request->hauler_driver_id)->first()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function editHaulerDriver(Request $request) {
        $validator = Validator::make($request->all(), [
                    'hauler_driver_id' => 'required',
                    'driver_first_name' => 'required',
                    'driver_last_name' => 'required',
                    'email' => 'required',
                    'driver_phone' => 'required',
//                    'driver_address' => 'required',
//                    'driver_city' => 'required',
//                    'driver_province' => 'required',
//                    'driver_zipcode' => 'required',
//                    'driver_type' => 'required',
//                    'driver_licence' => 'required',
//                    'driver_licence_image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }

        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $driver = User::whereId($request->hauler_driver_id)->first();
            if ($request->email != '' && $request->email != null) {
                if ($driver->email !== $request->email) {
                    $checkEmail = User::where('email', $request->email)->first();
                    if ($checkEmail !== null) {
                        if ($checkEmail->id !== $driver->id) {
                            return response()->json([
                                        'status' => false,
                                        'message' => 'Email is already taken.',
                                        'data' => []
                                            ], 422);
                        }
                    }
                    $confirmed = 0;
                }
            }
//            $checkDriverLicence = User::where('driver_licence', $request->driver_licence)->first();
//            if ($checkDriverLicence !== null) {
//                if ($checkDriverLicence->id !== $driver->id) {
//                    return response()->json([
//                                'status' => false,
//                                'message' => 'Driver lecience is already taken.',
//                                'data' => []
//                                    ], 422);
//                }
//            }
            try {
                DB::beginTransaction();
                if ($request->password != '' && $request->password != null) {
                    $driver->password = bcrypt($request->password);
                }
                $driver->prefix = (isset($request->driver_prefix) && $request->driver_prefix != '' && $request->driver_prefix != null) ? $request->driver_prefix : null;
                $driver->first_name = $request->driver_first_name;
                $driver->last_name = $request->driver_last_name;
                $driver->email = $request->email;
                $driver->phone = $request->driver_phone;
//                $driver->address = $request->driver_address;
//                $driver->city = $request->driver_city;
//                $driver->state = $request->driver_province;
//                $driver->zip_code = $request->driver_zipcode;
                $driver->user_image = (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null;
//                $driver->hauler_driver_licence = $request->driver_licence;
//                $driver->hauler_driver_licence_image = $request->driver_licence_image;
                if (isset($confirmed)) {
                    $driver->is_confirmed = $confirmed;
                }
                if ($driver->save()) {
                    DB::commit();
                    if (isset($confirmed)) {
                        $this->_updateEmail($driver, $request->email);
                    }
                    return response()->json([
                                'status' => true,
                                'message' => 'Driver details updated successfully.',
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function deleteHaulerDriver(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            try {
                DB::beginTransaction();
                $user = User::whereId($request->hauler_driver_id)->first();
                if(User::whereId($request->hauler_driver_id)->delete()) {
                    $customerActivity = new CustomerActivity([
                        'customer_id' => $user->created_from_id,
                        'created_by' => $request->user()->id,
                        'activities' => $user->first_name . ' is deleted by wellington office by ' . $request->user()->first_name,
                    ]);
                    if ($customerActivity->save()) {
                        
                        // Notification is required.
                        
                        DB::commit();
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Hauler driver deleted successfully.',
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

}