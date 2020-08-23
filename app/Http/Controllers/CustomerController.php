<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mail;
use App\Service;
use App\TimeSlots;
use App\ServicesTimeSlot;
use App\User;
use App\ManagerDetail;
use App\CustomerFarm;
use App\CustomerCardDetail;
use App\Payment;
use App\Job;
use Carbon\Carbon;

class CustomerController extends Controller {
    /**
     * list customer
     */
    public function listCustomer() {
        $getCustomer = User::with('farmlist.farmManager')
                        ->whereRoleId(config('constant.roles.Customer'))->get();
        return response()->json([
                    'status' => true,
                    'message' => 'Customer Listing.',
                    'data' => $getCustomer
                        ], 200);
    }
    /**
     * create customer
     */
    public function createCustomer(Request $request) {
        $validator = Validator::make($request->all(), [
                    'customer_first_name' => 'required|string',
                    'customer_last_name' => 'required|string',
                    'customer_email' => 'required|string|email|unique:users',
                    'customer_phone' => 'required',
                    'customer_address' => 'required',
                    'customer_city' => 'required',
                    'customer_province' => 'required',
                    'customer_zipcode' => 'required',
                    'customer_is_active' => 'required',
            
                    'farm_address' => 'required',
                    'farm_city' => 'required',
                    'farm_province' => 'required',
                    'farm_zipcode' => 'required',
                    'farm_active' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
            
                    'manager_details.*.manager_first_name' => 'required',
                    'manager_details.*.manager_last_name' => 'required',
                    'manager_details.*.manager_email' => 'required|email|unique:users',
                    'manager_details.*.manager_phone' => 'required',
                    'manager_details.*.manager_address' => 'required',
                    'manager_details.*.manager_city' => 'required',
                    'manager_details.*.manager_province' => 'required',
                    'manager_details.*.manager_zipcode' => 'required',
                    'manager_details.*.manager_card_image' => 'required',
                    'manager_details.*.manager_id_card' => 'required',
                    'manager_details.*.salary' => 'required',
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
                'prefix' => (isset($request->customer_prefix) && $request->customer_prefix != '' && $request->customer_prefix != null) ? $request->customer_prefix : null,
                'first_name' => $request->customer_first_name,
                'last_name' => $request->customer_last_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
                'address' => $request->customer_address,
                'city' => $request->customer_city,
                'state' => $request->customer_province,
                'zip_code' => $request->customer_zipcode,
                'user_image' => (isset($request->customer_image) && $request->customer_image != '' && $request->customer_image != null) ? $request->customer_image : null,
                'role_id' => config('constant.roles.Customer'),
                'created_from_id' => $request->user()->id,
                'is_confirmed' => 1,
                'is_active' => $request->customer_is_active,
                'password' => bcrypt($newPassword)
            ]);
            if ($user->save()) {
                $this->_confirmPassword($user, $newPassword);
                $farmDetails = new CustomerFarm([
                    'customer_id' => $user->id,
                    'farm_address' => $request->farm_address,
                    'farm_unit' => (isset($request->farm_unit) && $request->farm_unit != '' && $request->farm_unit != null) ? ($request->farm_unit) : null,
                    'farm_city' => $request->farm_city,
                    'farm_province' => $request->farm_province,
                    'farm_zipcode' => $request->farm_zipcode,
                    'farm_image' => (isset($request->farm_images) && $request->farm_images != '' && $request->farm_images != null) ? json_encode($request->farm_images) : null,
                    'farm_active' => $request->farm_active,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'created_by' => $request->user()->id,
                ]);
                if ($farmDetails->save()) {
                    foreach ($request->manager_details as $manager) {
                        $newPassword = Str::random();
                        $saveManger = new User([
                            'prefix' => (isset($manager['manager_prefix']) && $manager['manager_prefix'] != '' && $manager['manager_prefix'] != null) ? $manager['manager_prefix'] : null,
                            'first_name' => $manager['manager_first_name'],
                            'last_name' => $manager['manager_last_name'],
                            'email' => $manager['manager_email'],
                            'phone' => $manager['manager_phone'],
                            'address' => $manager['manager_address'],
                            'city' => $manager['manager_city'],
                            'state' => $manager['manager_province'],
                            'zip_code' => $manager['manager_zipcode'],
                            'user_image' => (isset($manager['manager_image']) && $manager['manager_image'] != '' && $manager['manager_image'] != null) ? $manager['manager_image'] : null,
                            'role_id' => config('constant.roles.Customer_Manager'),
                            'created_from_id' => $request->user()->id,
                            'is_confirmed' => 1,
                            'is_active' => 1,
                            'created_by' => $user->id,
                            'farm_id' => $farmDetails->id,
                            'password' => bcrypt($newPassword)
                        ]);

                        if ($saveManger->save()) {
                            $mangerDetails = new ManagerDetail([
                                'user_id' => $saveManger->id,
                                'identification_number' => $manager['manager_id_card'],
                                'document' => $manager['manager_card_image'],
                                'salary' => $manager['salary'],
                                'joining_date' => date('Y/m/d'),
                            ]);
                            if ($mangerDetails->save()) {
                                $this->_confirmPassword($saveManger, $newPassword);
                            }
                        }
                        DB::commit();
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Customer created successfully.',
                                    'data' => $user
                                        ], 200);
                    }
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
     * create customer farm
     */
    public function createFarm(Request $request) {
        $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'farm_address' => 'required',
                    'farm_city' => 'required',
                    'farm_province' => 'required',
                    'farm_zipcode' => 'required',
                    'farm_active' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
            
                    'manager_details.*.manager_first_name' => 'required',
                    'manager_details.*.manager_last_name' => 'required',
                    'manager_details.*.manager_email' => 'required|email|unique:users',
                    'manager_details.*.manager_phone' => 'required',
                    'manager_details.*.manager_address' => 'required',
                    'manager_details.*.manager_city' => 'required',
                    'manager_details.*.manager_province' => 'required',
                    'manager_details.*.manager_zipcode' => 'required',
                    'manager_details.*.manager_card_image' => 'required',
                    'manager_details.*.manager_id_card' => 'required',
                    'manager_details.*.salary' => 'required',
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
            $farmDetails = new CustomerFarm([
                'customer_id' => $request->customer_id,
                'farm_address' => $request->farm_address,
                    'farm_unit' => (isset($request->farm_unit) && $request->farm_unit != '' && $request->farm_unit != null) ? ($request->farm_unit) : null,
                    'farm_city' => $request->farm_city,
                    'farm_province' => $request->farm_province,
                    'farm_zipcode' => $request->farm_zipcode,
                    'farm_image' => (isset($request->farm_images) && $request->farm_images != '' && $request->farm_images != null) ? json_encode($request->farm_images) : null,
                    'farm_active' => $request->farm_active,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'created_by' => $request->user()->id,
            ]);
            if ($user->save()) {
                $this->_confirmPassword($user, $newPassword);
                $farmDetails = new CustomerFarm([
                    'customer_id' => $user->id,
                    'farm_address' => $request->farm_address,
                    'farm_unit' => (isset($request->farm_unit) && $request->farm_unit != '' && $request->farm_unit != null) ? ($request->farm_unit) : null,
                    'farm_city' => $request->farm_city,
                    'farm_province' => $request->farm_province,
                    'farm_zipcode' => $request->farm_zipcode,
                    'farm_image' => (isset($request->farm_images) && $request->farm_images != '' && $request->farm_images != null) ? json_encode($request->farm_images) : null,
                    'farm_active' => $request->farm_active,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'created_by' => $request->user()->id,
                ]);
                if ($farmDetails->save()) {
                    foreach ($request->manager_details as $manager) {
                        $newPassword = Str::random();
                        $saveManger = new User([
                            'prefix' => (isset($manager['manager_prefix']) && $manager['manager_prefix'] != '' && $manager['manager_prefix'] != null) ? $manager['manager_prefix'] : null,
                            'first_name' => $manager['manager_first_name'],
                            'last_name' => $manager['manager_last_name'],
                            'email' => $manager['manager_email'],
                            'phone' => $manager['manager_phone'],
                            'address' => $manager['manager_address'],
                            'city' => $manager['manager_city'],
                            'state' => $manager['manager_province'],
                            'zip_code' => $manager['manager_zipcode'],
                            'user_image' => (isset($manager['manager_image']) && $manager['manager_image'] != '' && $manager['manager_image'] != null) ? $manager['manager_image'] : null,
                            'role_id' => config('constant.roles.Customer_Manager'),
                            'created_from_id' => $request->user()->id,
                            'is_confirmed' => 1,
                            'is_active' => 1,
                            'created_by' => $user->id,
                            'farm_id' => $farmDetails->id,
                            'password' => bcrypt($newPassword)
                        ]);

                        if ($saveManger->save()) {
                            $mangerDetails = new ManagerDetail([
                                'user_id' => $saveManger->id,
                                'identification_number' => $manager['manager_id_card'],
                                'document' => $manager['manager_card_image'],
                                'salary' => $manager['salary'],
                                'joining_date' => date('Y/m/d'),
                            ]);
                            if($mangerDetails->save()) {
                                $this->_confirmPassword($saveManger, $newPassword);
                            }
                        }
                    }
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Customer created successfully.',
                                'data' => $user
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
     * create customer manager
     */
    public function createCustomerManager(Request $request) {
        $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'farm_id' => 'required',
                    'manager_first_name' => 'required',
                    'manager_last_name' => 'required',
                    'manager_email' => 'required|email|unique:users',
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
            $saveManager = new User([
                'prefix' => (isset($request->manager_prefix) && $request->manager_prefix != '' && $request->manager_prefix != null) ? $request->manager_prefix : null,
                'first_name' => $request->manager_first_name,
                'last_name' => $request->manager_last_name,
                'email' => $request->manager_email,
                'phone' => $request->manager_phone,
                'address' => $request->manager_address,
                'city' => $request->manager_city,
                'state' => $request->manager_province,
                'zip_code' => $request->manager_zipcode,
                'user_image' => (isset($request->manager_image) && $request->manager_image != '' && $request->manager_image != null) ? $request->manager_image : null,
                'role_id' => config('constant.roles.Customer_Manager'),
                'created_from_id' => $request->user()->id,
                'is_confirmed' => 1,
                'is_active' => 1,
                'created_by' => $request->customer_id,
                'farm_id' => $request->id,
                'password' => bcrypt($newPassword)
            ]);

            if ($saveManager->save()) {
                $managerDetails = new ManagerDetail([
                    'user_id' => $saveManager->id,
                    'identification_number' => $request->manager_id_card,
                    'document' => $request->manager_card_image,
                    'salary' => $request->salary,
                    'joining_date' => date('Y/m/d'),
                ]);
                if ($managerDetails->save()) {
                    $this->_confirmPassword($saveManager, $newPassword);
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Customer created successfully.',
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
     * get customer details
     * @param id
     * return customer array
     */
    public function getCustomer(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer Details',
                    'data' => User::whereId($request->customer_id)->first()
                        ], 200);
    }

    /**
     * get customer farm
     */
    public function getFarms(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer farms details',
                    'data' => CustomerFarm::where('customer_id', $request->customer_id)->get()
                        ], 200);
    }

    /**
     * get manager details
     */
    public function getCustomerManager(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer manager details',
                    'data' => [
                        'managerDetails' => User::where('created_by', $request->customer_id)->get(),
                        'farm' => CustomerFarm::where('customer_id', $request->customer_id)->get()
                    ]
                        ], 200);
    }

    public function getFarmManager(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer manager details',
                    'data' => CustomerFarm::where('farm_id', $request->farm_id)->with('farmManager')->get()
                        ], 200);
    }

    /*
     * get card listing based on customer id
     */

    public function getAllCard(Request $request) {
        $card = CustomerCardDetail::where('customer_id', $request->customer_id)->get();
        return response()->json([
                    'status' => true,
                    'message' => 'Card List successfully.',
                    'data' => $card
                        ], 200);
    }

    /*
     * get customer records by customer id
     */

    public function getAllRecords(Request $request) {
        $customer = User::where('id', $request->customer_id)->first();
        $memberSince = $customer->created_at;
        $farms = CustomerFarm::where('customer_id', $request->customer_id)->get();
        $totalFarms = $farms->count();
        $jobs = Job::where('customer_id', $request->customer_id)->with(['farm', 'truck_driver', 'skidsteer_driver', 'service'])->get();
        $totalJobs = $jobs->count();
        $lifetimeBilling = Payment::where('customer_id', $request->customer_id)->sum('amount');
        $last12MonthBilling = Payment::where('customer_id', $request->customer_id)->whereMonth(
                        'created_at', '=', Carbon::now()->subMonth(12)
                )->sum('amount');
        return response()->json([
                    'status' => true,
                    'message' => 'Card List successfully.',
                    'data' => [
                        'last12MonthBilling' => $last12MonthBilling,
                        'lifetimeBilling' => $lifetimeBilling,
                        'totalFarms' => $totalFarms,
                        'totalJobs' => $totalJobs,
                        'memberSince' => $memberSince,
                        'jobDetails' => $jobs
                    ]
                        ], 200);
    }

    /**
     * update customer
     */
    public function updateCustomer(Request $request) {
        $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'customer_first_name' => 'required|string',
                    'customer_last_name' => 'required|string',
                    'customer_email' => 'required|string|email|unique:users,email' . $request->customer_id,
                    'customer_phone' => 'required',
                    'customer_address' => 'required',
                    'customer_city' => 'required',
                    'customer_province' => 'required',
                    'customer_zipcode' => 'required',
                    'customer_is_active' => 'required',
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
            $customerDetails = User::whereId($request->customer_id)->first();
            if ($request->password != '' && $request->password != null) {
                $customerDetails->password = bcrypt($request->password);
            }
            $customerDetails->prefix = (isset($request->customer_prefix) && $request->customer_prefix != '' && $request->customer_prefix != null) ? $request->customer_prefix : null;
            $customerDetails->first_name = $request->customer_first_name;
            $customerDetails->last_name = $request->customer_last_name;
            $customerDetails->email = $request->customer_email;
            $customerDetails->phone = $request->customer_phone;
            $customerDetails->address = $request->customer_address;
            $customerDetails->city = $request->customer_city;
            $customerDetails->state = $request->customer_province;
            $customerDetails->zip_code = $request->customer_zipcode;
            $customerDetails->user_image = (isset($request->customer_image) && $request->customer_image != '' && $request->customer_image != null) ? $request->customer_image : null;
            $customerDetails->is_active = $request->customer_is_active;
            if ($customerDetails->save()) {
                DB::commit();
                return response()->json([
                            'status' => true,
                            'message' => 'Customer updated successfully.',
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
    /*
     * update farm details
     */
    public function updateFarm(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'farm_id' => 'required',
                        'farm_address' => 'required',
                        'farm_city' => 'required',
                        'farm_province' => 'required',
                        'farm_zipcode' => 'required',
                        'farm_active' => 'required',
                        'latitude' => 'required',
                        'longitude' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                            'status' => false,
                            'message' => 'The given data was invalid.',
                            'data' => $validator->errors()
                                ], 422);
            }
            CustomerFarm::whereId($request->farm_id)->update([
                'farm_address' => $request->farm_address,
                'farm_unit' => (isset($request->farm_unit) && $request->farm_unit != '' && $request->farm_unit != null) ? ($request->farm_unit) : null,
                'farm_city' => $request->farm_city,
                'farm_province' => $request->farm_province,
                'farm_zipcode' => $request->farm_zipcode,
                'farm_image' => (isset($request->farm_images) && $request->farm_images != '' && $request->farm_images != null) ? json_encode($request->farm_images) : null,
                'farm_active' => $request->farm_active,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return response()->json([
                        'status' => true,
                        'message' => 'Farm details updated successfully.',
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

    public function updateManager(Request $request) {
        $validator = Validator::make($request->all(), [
                    'manager_id' => 'required',
                    'farm_id' => 'required',
                    'manager_first_name' => 'required',
                    'manager_last_name' => 'required',
                    'manager_email' => 'required|email|unique:users,email.' . $request->manager_id,
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
        try {
            DB::beginTransaction();
            $manager = User::whereId($request->manager_id)->first();
            if ($request->password != '' && $request->password != null) {
                $manager->password = bcrypt($request->password);
            }
            $manager->prefix = (isset($request->manager_prefix) && $request->manager_prefix != '' && $request->manager_prefix != null) ? $request->manager_prefix : null;
            $manager->first_name = $request->manager_first_name;
            $manager->last_name = $request->manager_last_name;
            $manager->email = $request->manager_email;
            $manager->phone = $request->manager_phone;
            $manager->address = $request->manager_address;
            $manager->city = $request->manager_city;
            $manager->state = $request->manager_province;
            $manager->zip_code = $request->manager_zipcode;
            $manager->user_image = (isset($request->manager_image) && $request->manager_image != '' && $request->manager_image != null) ? $request->manager_image : null;
            $manager->is_active = $request->manager_is_active;
            $manager->farm_id = $request->farm_id;
            if ($manager->save()) {
                $managerDetail = ManagerDetail::where('user_id', $request->manager_id)->first();
                $managerDetail->salary = $request->salary;
                $managerDetail->identification_number = $request->manager_id_card;
                $managerDetail->joining_date = $request->joining_date;
                $managerDetail->releaving_date = isset($request->releaving_date) ? $request->releaving_date : null;
                $managerDetail->document = $request->manager_card_image;
                if ($managerDetail->save()) {
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Manager updated successfully.',
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

    /**
     * create customer farm
     */
//    public function createCustomerFarm(Request $request)
//    {
//        //validate request
//        $validator = Validator::make($request->all(), [
//            'customer_id' => 'required',
//	    'farm_address' => 'required',
//            'farm_city' => 'required',
//            'farm_province' => 'required',
//            'farm_unit' => 'required',
//            'farm_zipcode' => 'required',
//            'farm_active' => 'required',
//            'latitude' => 'required',
//            'longitude' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'status' => false,
//                'message' => 'The given data was invalid.',
//                'data' => $validator->errors()
//            ], 422);
//        }
//
//        try {
//
//            //use of db transactions
//            DB::beginTransaction();
//
//            //save customer farm details
//            $farmDetails = new CustomerFarm([
//                'customer_id' => $request->customer_id,
//                'farm_address' => $request->farm_address,
//                'farm_city' => $request->farm_city,
//                'farm_image' => isset($request->farm_images) ? json_encode($request->farm_images) : null,
//                'farm_province' => $request->farm_province,
//                'farm_unit' => $request->farm_unit,
//                'farm_zipcode' => $request->farm_zipcode,
//                'farm_active' => $request->farm_active,
//                'latitude' => $request->latitude,
//                'longitude' => $request->longitude
//            ]);
//
//            $farmDetails->save();
//
//            DB::commit();
//
//            //return success response
//            return response()->json([
//                'status' => true,
//                'message' => 'Customer created successfully.',
//                'data' => []
//            ], 200);
//        } catch (\Exception $e) {
//            //rollback transactions
//            DB::rollBack();
//            //make log of errors
//            Log::error(json_encode($e->getMessage()));
//            //return with error
//            return response()->json([
//                'status' => false,
//                'message' => $e->getMessage(),
//                'data' => []
//            ], 500);
//        }
//    }

    /**
     * list hauler
     */
//    public function listHauler() {
//        $getCustomer = User::with(['customerManager' => function ($query) {
//                                $query->with("manager", "farms");
//                            }])
//                        ->whereRoleId(config('constant.roles.Haulers'))->get();
//
//        return response()->json([
//                    'status' => true,
//                    'message' => 'Haulers Listing.',
//                    'data' => $getCustomer
//                        ], 200);
//    }

    /**
     * create hauler
     */
//    public function createHauler(Request $request) {
//        //validate request
//        $validator = Validator::make($request->all(), [
//                    'prefix' => 'required',
//                    'customer_first_name' => 'required|string',
//                    'customer_last_name' => 'required|string',
//                    'customer_email' => 'required|string|email|unique:users',
//                    'customer_phone' => 'required',
//                    'customer_address' => 'required',
//                    'customer_city' => 'required',
//                    'customer_province' => 'required',
//                    'customer_zipcode' => 'required',
//                    'customer_role_id' => 'required',
//                    'customer_is_active' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                        'status' => false,
//                        'message' => 'The given data was invalid.',
//                        'data' => $validator->errors()
//                            ], 422);
//        }
//
//        try {
//
//            //use of db transactions
//            DB::beginTransaction();
//
//            //random string for new password
//            $newPassword = Str::random();
//
//            //create new user
//            $user = new User([
//                'prefix' => $request->prefix,
//                'first_name' => $request->customer_first_name,
//                'last_name' => $request->customer_last_name,
//                'email' => $request->customer_email,
//                'phone' => $request->customer_phone,
//                'address' => $request->customer_address,
//                'city' => $request->customer_city,
//                'state' => $request->customer_province,
//                'zip_code' => $request->customer_zipcode,
//                'user_image' => isset($request->user_image) ? $request->user_image : null,
//                'role_id' => $request->customer_role_id,
//                'is_confirmed' => 1,
//                'is_active' => $request->customer_is_active,
//                'password' => bcrypt($newPassword)
//            ]);
//            if ($user->save()) {
//                //send email for new email and password
//                $this->_confirmPassword($user, $newPassword);
//            }
//            DB::commit();
//
//            //return success response
//            return response()->json([
//                        'status' => true,
//                        'message' => 'Customer created successfully.',
//                        'data' => []
//                            ], 200);
//        } catch (\Exception $e) {
//            //rollback transactions
//            DB::rollBack();
//            //make log of errors
//            Log::error(json_encode($e->getMessage()));
//            //return with error
//            return response()->json([
//                        'status' => false,
//                        'message' => $e->getMessage(),
//                        'data' => []
//                            ], 500);
//        }
//    }

}
