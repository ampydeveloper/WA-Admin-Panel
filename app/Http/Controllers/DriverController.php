<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use Validator;
use App\Driver;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DriverController extends Controller {

    public function listDrivers(Request $request) {

        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Drivers Details',
                        'data' => User::where('role_id', config('constant.roles.Driver'))->with('driver')->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function listDriversMobile(Request $request) {
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
                        'message' => 'Drivers Details',
                        'data' => User::where('role_id', config('constant.roles.Driver'))->with('driver')->skip($request->offset)->take($request->take)->orderBy('id', 'DESC')->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function createDriver(Request $request) {
        $validator = Validator::make($request->all(), [
                    'driver_first_name' => 'required',
                    'driver_last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'driver_phone' => 'required',
                    'driver_address' => 'required',
                    'driver_city' => 'required',
                    'driver_province' => 'required',
                    'driver_zipcode' => 'required',
//                    'driver_type' => 'required',
                    'driver_licence' => 'required|unique:drivers',
                    'expiry_date' => 'required',
                    'salary_type' => 'required',
                    'driver_salary' => 'required',
                    'driver_licence_image' => 'required',
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
                    'prefix' => (isset($request->driver_prefix) && $request->driver_prefix != '' && $request->driver_prefix != null) ? $request->driver_prefix : null,
                    'first_name' => $request->driver_first_name,
                    'last_name' => $request->driver_last_name,
                    'email' => $request->email,
                    'phone' => $request->driver_phone,
                    'address' => $request->driver_address,
                    'city' => $request->driver_city,
                    'state' => $request->driver_province,
                    'zip_code' => $request->driver_zipcode,
                    'user_image' => (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null,
                    'role_id' => config('constant.roles.Driver'),
                    'created_from_id' => $request->user()->id,
                    'is_active' => 1,
                    'is_confirmed' => 1,
                    'password' => bcrypt($newPassword)
                ]);
                if ($user->save()) {
                    $driverDetails = new Driver([
                        'user_id' => $user->id,
                        'driver_type' => 1,
                        'driver_licence' => $request->driver_licence,
                        'expiry_date' => $request->expiry_date,
                        'document' => $request->driver_licence_image,
                        'salary_type' => $request->salary_type,
                        'driver_salary' => $request->driver_salary,
                        'status' => config('constant.driver_status.available'),
                    ]);
                    if ($driverDetails->save()) {
                        $this->_confirmPassword($user, $newPassword);
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

    public function editDriver(Request $request) {
        $validator = Validator::make($request->all(), [
                    'driver_id' => 'required',
                    'driver_first_name' => 'required',
                    'driver_last_name' => 'required',
                    'email' => 'required',
                    'driver_phone' => 'required',
                    'driver_address' => 'required',
                    'driver_city' => 'required',
                    'driver_province' => 'required',
                    'driver_zipcode' => 'required',
//                    'driver_type' => 'required',
                    'driver_licence' => 'required',
                    'expiry_date' => 'required',
                    'driver_licence_image' => 'required',
                    'salary_type' => 'required',
                    'driver_salary' => 'required',
                    'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }

        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $driver = User::whereId($request->driver_id)->first();
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
            $checkDriverLicence = Driver::where('driver_licence', $request->driver_licence)->first();
            $driverDetail = Driver::where('user_id', $request->driver_id)->first();
            if ($checkDriverLicence !== null) {
                if ($checkDriverLicence->id !== $driverDetail->id) {
                    return response()->json([
                                'status' => false,
                                'message' => 'Driver lecience is already taken.',
                                'data' => []
                                    ], 422);
                }
            }
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
                $driver->address = $request->driver_address;
                $driver->city = $request->driver_city;
                $driver->state = $request->driver_province;
                $driver->zip_code = $request->driver_zipcode;
                $driver->user_image = (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null;
                $driver->is_active = $request->status;
                if (isset($confirmed)) {
                    $driver->is_confirmed = $confirmed;
                }
                if ($driver->save()) {
                    if ($driver->role_id != config('constant.roles.Admin')) {
                        Driver::whereUserId($request->driver_id)->update([
                            'driver_type' => 1,
                            'driver_licence' => $request->driver_licence,
                            'expiry_date' => $request->expiry_date,
                            'document' => $request->driver_licence_image,
                            'salary_type' => $request->salary_type,
                            'driver_salary' => $request->driver_salary,
                            'status' => $request->status,
                        ]);
                    }
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

    public function getDriver(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Driver Details',
                        'data' => User::whereId($request->driver_id)->with('driver')->first()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function deleteDriver(Request $request) {

        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            try {
                User::whereId($request->driver_id)->delete();
                return response()->json([
                            'status' => true,
                            'message' => 'Driver deleted successfully.',
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

}
