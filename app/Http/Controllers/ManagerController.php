<?php

namespace App\Http\Controllers;

use Mail;
use App\Job;
use App\User;
use Validator;
use App\Service;
use App\Vehicle;
//use Carbon\Carbon;
use App\ManagerDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Auth;
use Bioudi\LaravelMetaWeatherApi\Weather;

class ManagerController extends Controller {
    
    
    public function dashboard(Request $request) {
        
        $weather = new Weather();
        $weatherDetails = $weather->getByCoordinates(36.96, -122.02, '2018/01/01');
        $weatherDetails = $weather->getByCityName('miami');
        $data['weather']['weather_state_name'] = $weatherDetails->consolidated_weather[0]->weather_state_name;
        $data['weather']['wind_direction_compass'] = $weatherDetails->consolidated_weather[0]->wind_direction_compass;
        $data['weather']['min_temp'] = $weatherDetails->consolidated_weather[0]->min_temp;
        $data['weather']['max_temp'] = bcdiv($weatherDetails->consolidated_weather[0]->max_temp, 1, 2);
        $data['weather']['the_temp'] = $weatherDetails->consolidated_weather[0]->the_temp;
        $data['weather']['wind_speed'] = bcdiv($weatherDetails->consolidated_weather[0]->wind_speed, 1, 2)." miles";
        $data['weather']['air_pressure'] = $weatherDetails->consolidated_weather[0]->air_pressure." mbar";
        $data['weather']['humidity'] = $weatherDetails->consolidated_weather[0]->humidity."%";
        $data['weather']['visibility'] = bcdiv($weatherDetails->consolidated_weather[0]->visibility, 1, 2)." miles";
        $data['weather']['predictability'] = $weatherDetails->consolidated_weather[0]->predictability."%";
        $data['weather']['weather_icon'] = "https://www.metaweather.com/static/img/weather/png/64/".$weatherDetails->consolidated_weather[0]->weather_state_abbr.".png";
        
        
        $startDate = date('Y-m-d', strtotime('-7 days'));
        
        $data['count']['services'] = Service::get()->count();
        $data['count']['managers'] = User::where('role_id', config('constant.roles.Admin_Manager'))->get()->count();
        $data['count']['drivers'] = User::where('role_id', config('constant.roles.Driver'))->get()->count();
        $data['count']['trucks'] = Vehicle::where('vehicle_type', config('constant.vehicle_type.truck'))->get()->count();
        $data['count']['skidsteers'] = Vehicle::where('vehicle_type', config('constant.vehicle_type.skidsteer'))->get()->count();
        
        // Graphs
        $allCustomer = User::where('role_id', config('constant.roles.Customer'))->get();
        $data['graphs']['allCustomersCount'] = $allCustomer->count();

        $newCustomers = User::where('role_id', config('constant.roles.Customer'))->whereBetween('created_at',[$startDate,date('Y-m-d')])->get()->groupBy('created_at');
        $data['graphs']['newCustomersCount'] = $newCustomers->count();
        
        $counter = 0;
        $data['graphs']['newCustomerGraph'] = [];
        foreach($newCustomers as $key => $value) {
            $data['graphs']['newCustomerGraph'][$counter]['date'] = $key;
            $data['graphs']['newCustomerGraph'][$counter]['no'] = count($value);
            $counter++;
        }
        $data['graphs']['revenueGeneratedByNewCustomers'] = Job::whereHas('customer', function($q) use($startDate) {
            $q->where('role_id', config('constant.roles.Customer'))->whereBetween('created_at',[$startDate,date('Y-m-d')]);
        })->sum('amount');
        
        $allHaulers = User::where('role_id', config('constant.roles.Haulers'))->get();
        $data['graphs']['allHaulersCount'] = $allHaulers->count();
        
        $newHaulers = User::where('role_id', config('constant.roles.Haulers'))->whereBetween('created_at',[$startDate,date('Y-m-d')])->get()->groupBy('created_at');
        $data['graphs']['newHaulersCount'] = $newHaulers->count();
        
        $counter = 0;
        $data['graphs']['newHaulerGraph'] = [];
        foreach($newHaulers as $key => $value) {
            $data['graphs']['newHaulerGraph'][$counter]['date'] = $key;
            $data['graphs']['newHaulerGraph'][$counter]['no'] = count($value);
            $counter++;
        }
        
        $data['graphs']['revenueGeneratedByNewHaulers'] = Job::whereHas('customer', function($q) use($startDate) {
            $q->where('role_id', config('constant.roles.Haulers'))->whereBetween('created_at',[$startDate,date('Y-m-d')]);
        })->sum('amount');
        
        $allJobs = Job::get();
        $data['graphs']['allJobsCount'] = $allJobs->count();
        
        $newJobs = Job::whereBetween('created_at', [$startDate,date('Y-m-d')])->get()->groupBy('created_at');
        $data['graphs']['newJobsCount'] = $newJobs->count();

        //generating job graph
        $counter = 0;
        $data['graphs']['newJobGraph'] = [];
        foreach($newJobs as $key => $value) {
            $data['graphs']['newJobGraph'][$counter]['date'] = $key;
            $data['graphs']['newJobGraph'][$counter]['no'] = count($value);
            $counter++;
        }
        $data['graphs']['revenueGeneratedByNewJobs'] = Job::whereBetween('created_at', [$startDate,date('Y-m-d')])->sum('amount');

        //invoices
        $data['invoiceGraphs']['customerInvoices'] = Job::whereHas('customer', function($q) {
            $q->where('role_id', config('constant.roles.Customer'));
        })->where('payment_status', config('constant.payment_status.paid'))->whereBetween('created_at', [$startDate,date('Y-m-d')])->sum('amount');
        
        $data['invoiceGraphs']['haulerInvoices'] = Job::whereHas('customer', function($q) {
            $q->where('role_id', config('constant.roles.Haulers'));
        })->where('payment_status', config('constant.payment_status.paid'))->whereBetween('created_at', [$startDate,date('Y-m-d')])->sum('amount');
        
        $data['invoiceGraphs']['outstandingInvoices'] = Job::whereHas('customer', function($q) {
            $q->where('role_id', config('constant.roles.Haulers'));
        })->where('payment_status', config('constant.payment_status.unpaid'))->where(function($q) {
            $q->where('payment_mode', config('constant.payment_mode.cheque'))->orWhere('payment_mode', config('constant.payment_mode.cash'));
        })->whereBetween('created_at', [$startDate,date('Y-m-d')])->sum('amount');
        
        return response()->json([
                            'status' => true,
                            'message' => 'Dashboard data.',
                            'data' => $data
                                ], 200);
    }
    
    public function dashboardFilters(Request $request) {
        $validator = Validator::make($request->all(), [
                    'filter_for' => 'required',
                    'filter_time' => 'required',
                    'filter_category' => 'required_if:filter_for,==,graphs',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        
        if ($request->filter_time == '7 days' || $request->filter_time == 'last 7 days') {
            $startDate = date('Y-m-d', strtotime('-7 days'));
        } elseif ($request->filter_time == '1 month' || $request->filter_time == 'last month') {
            $startDate = date('Y-m-d', strtotime('-1 month'));
        } else {
            $startDate = date('Y-m-d', strtotime('-1 year'));
        }


        if ($request->filter_for == 'graphs') {
            if($request->filter_category == 'customers') {
                $newCustomers = User::where('role_id', config('constant.roles.Customer'))->whereBetween('created_at', [$startDate, date('Y-m-d')])->get()->groupBy('created_at');
                $data['graphs']['newCustomersCount'] = $newCustomers->count();
                $counter = 0; $data['graphs']['newCustomerGraph'] = [];
                foreach ($newCustomers as $key => $value) {
                    $data['graphs']['newCustomerGraph'][$counter]['date'] = $key;
                    $data['graphs']['newCustomerGraph'][$counter]['no'] = count($value);
                    $counter++;
                }
                $data['graphs']['revenueGeneratedByNewCustomers'] = Job::whereHas('customer', function($q) use($startDate) {
                            $q->where('role_id', config('constant.roles.Customer'))->whereBetween('created_at', [$startDate, date('Y-m-d')]);
                        })->sum('amount');
            } elseif ($request->filter_category == 'haulers') {
                $newHaulers = User::where('role_id', config('constant.roles.Haulers'))->whereBetween('created_at', [$startDate, date('Y-m-d')])->get()->groupBy('created_at');
                $data['graphs']['newHaulersCount'] = $newHaulers->count();
                $counter = 0; $data['graphs']['newHaulerGraph'] = [];
                foreach ($newHaulers as $key => $value) {
                    $data['graphs']['newHaulerGraph'][$counter]['date'] = $key;
                    $data['graphs']['newHaulerGraph'][$counter]['no'] = count($value);
                    $counter++;
                }

                $data['graphs']['revenueGeneratedByNewHaulers'] = Job::whereHas('customer', function($q) use($startDate) {
                            $q->where('role_id', config('constant.roles.Haulers'))->whereBetween('created_at', [$startDate, date('Y-m-d')]);
                        })->sum('amount');
            } else {
                $newJobs = Job::whereBetween('created_at', [$startDate, date('Y-m-d')])->get()->groupBy('created_at');
                $data['graphs']['newJobsCount'] = $newJobs->count();
                $counter = 0;
                $data['graphs']['newJobGraph'] = [];
                foreach ($newJobs as $key => $value) {
                    $data['graphs']['newJobGraph'][$counter]['date'] = $key;
                    $data['graphs']['newJobGraph'][$counter]['no'] = count($value);
                    $counter++;
                }
                $data['graphs']['revenueGeneratedByNewJobs'] = Job::where('job_status', '!=', config('constant.job_status.cancelled'))->whereBetween('created_at', [$startDate, date('Y-m-d')])->sum('amount');
            }
        } else {
            $data['invoiceGraphs']['customerInvoices'] = Job::whereHas('customer', function($q) {
                        $q->where('role_id', config('constant.roles.Customer'));
                    })->where('payment_status', config('constant.payment_status.paid'))->whereBetween('created_at',[$startDate,date('Y-m-d')])->sum('amount');

            $data['invoiceGraphs']['haulerInvoices'] = Job::whereHas('customer', function($q) {
                        $q->where('role_id', config('constant.roles.Haulers'));
                    })->where('payment_status', config('constant.payment_status.paid'))->whereBetween('created_at',[$startDate,date('Y-m-d')])->sum('amount');

            $data['invoiceGraphs']['outstandingInvoices'] = Job::whereHas('customer', function($q) {
                        $q->where('role_id', config('constant.roles.Haulers'));
                    })->where('payment_status', config('constant.payment_status.unpaid'))->where(function($q) {
                        $q->where('payment_mode', config('constant.payment_mode.cheque'))->orWhere('payment_mode', config('constant.payment_mode.cash'));
                    })->whereBetween('created_at',[$startDate,date('Y-m-d')])->sum('amount');
        }
        return response()->json([
                            'status' => true,
                            'message' => 'Dashboard data.',
                            'data' => $data
                                ], 200);
    }
    
    public function editProfile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required',
                    'phone' => 'required_if:role_id,==,config("constant.roles.Admin_Manager")',
                    'address' => 'required_if:role_id,==,config("constant.roles.Admin_Manager")',
                    'city' => 'required_if:role_id,==,config("constant.roles.Admin_Manager")',
                    'province' => 'required_if:role_id,==,config("constant.roles.Admin_Manager")',
                    'zipcode' => 'required_if:role_id,==,config("constant.roles.Admin_Manager")',
                    'is_active' => 'required_if:role_id,==,config("constant.roles.Admin_Manager")',
                    'identification_number' => 'required_if:role_id,==,config("constant.roles.Admin_Manager")',
                    'id_photo' => 'required_if:role_id,==,config("constant.roles.Admin_Manager")',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        $manager = $request->user();
        if ($request->email != '' && $request->email != null) {
            if ($manager->email !== $request->email) {
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
            $manager->phone = (isset($request->phone) && $request->phone != '' && $request->phone != null) ? $request->phone : null;
            $manager->address = (isset($request->address) && $request->address != '' && $request->address != null) ? $request->address : null;
            $manager->city = (isset($request->city) && $request->city != '' && $request->city != null) ? $request->city : null;
            $manager->state = (isset($request->province) && $request->province != '' && $request->province != null) ? $request->province : null;
            $manager->zip_code = (isset($request->zipcode) && $request->zipcode != '' && $request->zipcode != null) ? $request->zipcode : null;
            $manager->user_image = (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null;
            $manager->is_active = (isset($request->is_active) && $request->is_active != '' && $request->is_active != null) ? $request->is_active : null;
            if(isset($confirmed)) {
                $manager->is_confirmed = $confirmed;
            }
            if ($manager->save()) {
                if ($manager->role_id != config('constant.roles.Admin')) {
                    ManagerDetail::whereUserId($manager->id)->update([
                        'identification_number' => (isset($request->identification_number) && $request->identification_number != '' && $request->identification_number != null) ? $request->identification_number : null,
                        'joining_date' => (isset($request->joining_date) && $request->joining_date != '' && $request->joining_date != null) ? $request->joining_date : null,
                        'document' => (isset($request->id_photo) && $request->id_photo != '' && $request->id_photo != null) ? $request->id_photo : null,
                    ]);
                }
                DB::commit();
                if (isset($confirmed)) {
                    $this->_updateEmail($manager, $request->email);
                        $request->user()->token()->revoke();
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Successfully logged out',
                                    'data' => []
                        ]);
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

    public function createAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
                    'admin_first_name' => 'required',
                    'admin_last_name' => 'required',
                    'email' => 'required|email|unique:users',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin')) {
            try {
                DB::beginTransaction();
                $newPassword = Str::random();
                $user = new User([
                    'first_name' => $request->admin_first_name,
                    'last_name' => $request->admin_last_name,
                    'email' => $request->email,
                    'user_image' => (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null,
                    'role_id' => config('constant.roles.Admin'),
                    'created_from_id' => $request->user()->id,
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function editAdminProfile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'admin_id' => 'required',
                    'admin_first_name' => 'required|string',
                    'admin_last_name' => 'required|string',
                    'email' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin')) {
            $admin = User::whereId($request->admin_id)->first();
            if ($admin) {
                if ($request->email != '' && $request->email != null) {
                    if ($admin->email !== $request->email) {
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
                    $admin->user_image = (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null;
                    if (isset($confirmed)) {
                        $admin->is_confirmed = $confirmed;
                    }

                    $admin->save();
                    if (isset($confirmed)) {
                        $this->_updateEmail($admin, $request->email);
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
            return response()->json([
                        'status' => false,
                        'message' => "No admin.",
                        'data' => []
                            ], 500);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

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
                    'identification_number' => 'required|unique:manager_details',
                    'id_photo' => 'required',
                    'salary' => 'required',
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
                    'phone' => $request->manager_phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->province,
                    'zip_code' => $request->manager_zipcode,
                    'user_image' => (isset($request->user_image) && $request->user_image != '' && $request->user_image != null) ? $request->user_image : null,
                    'role_id' => config('constant.roles.Admin_Manager'),
                    'created_from_id' => $request->user()->id,
                    'is_active' => 1,
                    'is_confirmed' => 1,
                    'password' => bcrypt($newPassword)
                ]);
                if ($user->save()) {
                    $managerDetails = new ManagerDetail([
                        'user_id' => $user->id,
                        'identification_number' => $request->identification_number,
                        'document' => $request->id_photo,
                        'salary' => $request->salary,
                        'joining_date' => (isset($request->joining_date) && $request->joining_date != '' && $request->joining_date != null) ? $request->joining_date : date('Y/m/d'),
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function updateManager(Request $request) {
        $validator = Validator::make($request->all(), [
                    'manager_id' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'zipcode' => 'required',
                    'is_active' => 'required',
                    'identification_number' => 'required',
                    'id_photo' => 'required',
                    'salary' => 'required',
                    'joining_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $manager = User::whereId($request->manager_id)->first();
            if ($manager) {
                if ($request->email != '' && $request->email != null) {
                    if ($manager->email !== $request->email) {
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
                    if (isset($confirmed)) {
                        $manager->is_confirmed = $confirmed;
                    }
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
                        if (isset($confirmed)) {
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
            return response()->json([
                        'status' => false,
                        'message' => "No manager.",
                        'data' => []
                            ], 500);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getManager(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                    'status' => true,
                    'message' => 'Manager Details',
                    'data' => ManagerDetail::whereUserId($request->manager_id)->with('user')->first()
                        ], 200);
        }
        return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
    }
    
    public function getAdmin(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Admin Details',
                        'data' => User::whereId($request->admin_id)->first()
                            ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
    }

    public function deleteManager(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin')) {
            if ($request->manager_id == 1) {
                return response()->json([
                            'status' => false,
                            'message' => 'You cannot delete super admin.',
                            'data' => []
                                ], 421);
            }
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    
    public function listManager(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Manager List',
                        'data' => User::whereRoleId(config('constant.roles.Admin_Manager'))->with('managerDetails')->get()
                            ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
    }

    public function listManagerMobile(Request $request) {
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
                        'message' => 'Admin List',
                        'data' => User::whereRoleId(config('constant.roles.Admin_Manager'))->with('managerDetails')->skip($request->offset)->take($request->take)->orderBy('id', 'DESC')->get()
                            ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
    }

    public function listAdmin(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Admin List',
                        'data' => User::whereRoleId(config('constant.roles.Admin'))->get()
                            ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
    }

    public function listAdminMobile(Request $request) {
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
        
        if ($request->user()->role_id == config('constant.roles.Admin')) {
            return response()->json([
                    'status' => true,
                    'message' => 'Admin List',
                    'data' => User::whereRoleId(config('constant.roles.Admin'))->skip($request->offset)->take($request->take)->orderBy('id', 'DESC')->get()
                        ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
        
    }
    
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
    
    public function mechanicList(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Mechanic List',
                        'data' => User::whereRoleId(config('constant.roles.Mechanic'))->get()
                            ], 200);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
    }

    public function createMechanic(Request $request) {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users',
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
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'role_id' => config('constant.roles.Mechanic'),
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
                                'message' => 'Mechanic created successfully.',
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
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
    }

    public function singleMechanic(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                    'status' => true,
                    'message' => 'Mechanic Details',
                    'data' => User::whereId($request->mechanic_id)->first()
                        ], 200);
        } 
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
        
    }
    public function updateMechanic(Request $request) {

        $validator = Validator::make($request->all(), [
                    'mechanic_id' => 'required',
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $confirmed = 1;
            $mechanic = User::whereId($request->mechanic_id)->first();
            if ($mechanic) {
                if ($request->email != '' && $request->email != null) {
                    $checkEmail = User::where('email', $request->email)->first();
                    if ($checkEmail !== null) {
                        if ($checkEmail->id !== $mechanic->id) {
                            return response()->json([
                                        'status' => false,
                                        'message' => 'Email is already taken.',
                                        'data' => []
                                            ], 422);
                        }
                    }
                    if ($mechanic->email !== $request->email) {
                        $confirmed = 0;
                    }
                }
                try {
                    if ($request->password != '' && $request->password != null) {
                        $mechanic->password = bcrypt($request->password);
                    }
                    $mechanic->first_name = $request->first_name;
                    $mechanic->last_name = $request->last_name;
                    $mechanic->email = $request->email;
                    $mechanic->is_confirmed = $confirmed;
                    $mechanic->save();
                    if ($confirmed == 0) {
                        $this->_updateEmail($mechanic, $request->email);
                    }
                    return response()->json([
                                'status' => true,
                                'message' => 'Mechanic updated sucessfully.',
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
            return response()->json([
                        'status' => false,
                        'message' => "No Mechanic.",
                        'data' => []
                            ], 500);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function deleteMechanic(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            try {
                User::whereId($request->mechanic_id)->delete();
                return response()->json([
                            'status' => true,
                            'message' => 'Mechanic deleted successfully.',
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
        return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                        ], 421);
    }
    
//    if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
//        
//    } else {
//        return response()->json([
//                    'status' => false,
//                    'message' => 'Unauthorized access.',
//                        ], 421);
//    }

}