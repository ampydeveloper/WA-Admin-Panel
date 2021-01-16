<?php

namespace App\Http\Controllers;

use Mail;
use App\Job;
use App\User;
use App\Payment;
//use App\Service;
//use App\TimeSlots;
use Carbon\Carbon;
use App\CustomerFarm;
use App\ManagerDetail;
use App\CustomerActivity;
//use App\ServicesTimeSlot;
use App\CustomerCardDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
//use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller {

    /**
     * list customer
     */
    public function listCustomer(Request $request) {

        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $getCustomer = User::where('role_id', config('constant.roles.Customer'))->with(['farmlist' => function($q) {
                            $q->with('farmManager')->withCount('totalJobs')->with('latestJob');
                        }])->get();
            return response()->json([
                        'status' => true,
                        'message' => 'Customer Listing.',
                        'data' => $getCustomer
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function listCustomerMobile(Request $request) {
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
            $getCustomer = User::where('role_id', config('constant.roles.Customer'))->with(['farmlist' => function($q) {
                            $q->with('farmManager')->withCount('totalJobs')->with('latestJob');
                        }])->skip($request->offset)->take($request->take)->get();
            return response()->json([
                        'status' => true,
                        'message' => 'Customer Listing.',
                        'data' => $getCustomer
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function createCustomer(Request $request) {
        $validator = Validator::make($request->all(), [
                    'customer_first_name' => 'required|string',
                    'customer_last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users',
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
                    'manager_details.*.email' => 'required|email|unique:users',
                    'manager_details.*.manager_phone' => 'required',
                    'manager_details.*.manager_address' => 'required',
                    'manager_details.*.manager_city' => 'required',
                    'manager_details.*.manager_province' => 'required',
                    'manager_details.*.manager_zipcode' => 'required',
//                    'manager_details.*.manager_card_image' => 'required',
//                    'manager_details.*.manager_id_card' => 'required',
//                    'manager_details.*.salary' => 'required',
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
                    'first_name' => $request->customer_first_name,
                    'last_name' => $request->customer_last_name,
                    'email' => $request->email,
                    'phone' => $request->customer_phone,
                    'address' => $request->customer_address,
                    'city' => $request->customer_city,
                    'state' => $request->customer_province,
                    'zip_code' => $request->customer_zipcode,
                    'user_image' => (isset($request->customer_image) && $request->customer_image != '' && $request->customer_image != null) ? $request->customer_image : null,
                    'role_id' => config('constant.roles.Customer'),
                    'created_from_id' => $request->user()->id,
                    'is_confirmed' => 1,
                    'is_active' => 1,
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
                            'distance_warehouse' => $this->getDistance($request->latitude, $request->longitude, null, null, 'M', 'warehouse'),
                            'distance_dumping_area' => $this->getDistance($request->latitude, $request->longitude, null, null, 'M', 'dumping'),
                            'created_by' => $request->user()->id,
                        ]);
                        if ($farmDetails->save()) {
                            $customerActivity = new CustomerActivity([
                                'customer_id' => $user->id,
                                'created_by' => $request->user()->id,
                                'activities' => 'Farm located at ' . $farmDetails->farm_address . ' from wellington office by ' . $request->user()->first_name,
                            ]);
                            if ($customerActivity->save()) {
                                // Notification is required.
                                foreach ($request->manager_details as $manager) {
                                    $newPassword = Str::random();
                                    $saveManger = new User([
                                        'prefix' => (isset($manager['manager_prefix']) && $manager['manager_prefix'] != '' && $manager['manager_prefix'] != null) ? $manager['manager_prefix'] : null,
                                        'first_name' => $manager['manager_first_name'],
                                        'last_name' => $manager['manager_last_name'],
                                        'email' => $manager['email'],
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
                                        $this->_confirmPassword($saveManger, $newPassword);
                                        $customerActivity = new CustomerActivity([
                                            'customer_id' => $user->id,
                                            'created_by' => $request->user()->id,
                                            'activities' => $saveManger->first_name . ' is added as manager for farm at ' . $farmDetails->farm_address . ' from wellington office by ' . $request->user()->first_name,
                                        ]);
                                        $customerActivity->save();
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
    
    public function getDistance($lat1, $lon1, $lat2, $lon2, $unit, $flag)
    {
        if($flag == 'warehouse') {
            $lat2 = ($lat2 == null) ? config('constant.warehouse.lat') : $lat2;
            $lon2 = ($lon2 == null) ? config('constant.warehouse.lon') : $lon2;
        } else {
            $lat2 = ($lat2 == null) ? config('constant.dumping_area.lat') : $lat2;
            $lon2 = ($lon2 == null) ? config('constant.dumping_area.lon') : $lon2;
        }
        
        $lat1 = (float)$lat1;
        $lat2 = (float)$lat2;
        $lon1 = (float)$lon1;
        $lon2 = (float)$lon2;

        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
          }
          else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
        
            if ($unit == "K") {
              return (int) ($miles * 1.609344);
            } else if ($unit == "N") {
              return (int) ($miles * 0.8684);
            } else {
              return (int) $miles;
            }
          }
    }

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
                    'manager_details.*.email' => 'required|email|unique:users',
                    'manager_details.*.manager_phone' => 'required',
                    'manager_details.*.manager_address' => 'required',
                    'manager_details.*.manager_city' => 'required',
                    'manager_details.*.manager_province' => 'required',
                    'manager_details.*.manager_zipcode' => 'required',
                    'manager_details.*.manager_card_image' => 'required',
                    'manager_details.*.manager_id_card' => 'required',
//                    'manager_details.*.salary' => 'required',
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
                    'distance_warehouse' => $this->getDistance($request->latitude, $request->longitude, null, null, 'M', 'warehouse'),
                    'distance_dumping_area' => $this->getDistance($request->latitude, $request->longitude, null, null, 'M', 'dumping')
                ]);
                if ($farmDetails->save()) {
                    $customerActivity = new CustomerActivity([
                                'customer_id' => $request->customer_id,
                                'created_by' => $request->user()->id,
                                'activities' => 'Farm located at ' . $farmDetails->farm_address . ' from wellington office by ' . $request->user()->first_name,
                            ]);
                    
                    if ($customerActivity->save()) {
                        foreach ($request->manager_details as $manager) {
                            $newPassword = Str::random();
                            $saveManger = new User([
                                'prefix' => (isset($manager['manager_prefix']) && $manager['manager_prefix'] != '' && $manager['manager_prefix'] != null) ? $manager['manager_prefix'] : null,
                                'first_name' => $manager['manager_first_name'],
                                'last_name' => $manager['manager_last_name'],
                                'email' => $manager['email'],
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
                                'created_by' => $request->customer_id,
                                'farm_id' => $farmDetails->id,
                                'password' => bcrypt($newPassword)
                            ]);

                            if ($saveManger->save()) {
                                $this->_confirmPassword($saveManger, $newPassword);
                                $customerActivity = new CustomerActivity([
                                    'customer_id' => $request->customer_id,
                                    'created_by' => $request->user()->id,
                                    'activities' => $saveManger->first_name . ' is added as manager for farm at ' . $farmDetails->farm_address . ' from wellington office by ' . $request->user()->first_name,
                                ]);
                                $customerActivity->save();
                                // Notification is required.
                            }
                        }
                    }
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Customer farm created successfully.',
                                'data' => $farmDetails
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

    /**
     * create customer manager
     */
    public function createCustomerManager(Request $request) {
        $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'farm_id' => 'required',
                    'manager_first_name' => 'required',
                    'manager_last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'manager_phone' => 'required',
                    'manager_address' => 'required',
                    'manager_city' => 'required',
                    'manager_province' => 'required',
                    'manager_zipcode' => 'required',
//                    'manager_card_image' => 'required',
//                    'manager_id_card' => 'required',
//                    'salary' => 'required',
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
                $saveManager = new User([
                    'prefix' => (isset($request->manager_prefix) && $request->manager_prefix != '' && $request->manager_prefix != null) ? $request->manager_prefix : null,
                    'first_name' => $request->manager_first_name,
                    'last_name' => $request->manager_last_name,
                    'email' => $request->email,
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
                    'farm_id' => $request->farm_id,
                    'password' => bcrypt($newPassword)
                ]);

                if ($saveManager->save()) {
                    $this->_confirmPassword($saveManager, $newPassword);
                    $farmDetails = CustomerFarm::where('id', $request->farm_id)->first();
                    $customerActivity = new CustomerActivity([
                        'customer_id' => $request->customer_id,
                        'created_by' => $request->user()->id,
                        'activities' => $saveManager->first_name . ' is added as manager for farm at ' . $farmDetails->farm_address . ' from wellington office by ' . $request->user()->first_name,
                    ]);
                    if($customerActivity->save()) {
                        // Notification is required.
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getCustomer(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Customer Details',
                        'data' => User::whereId($request->customer_id)->first()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getFarms(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Customer farms details',
                        'data' => CustomerFarm::where('customer_id', $request->customer_id)->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getCustomerManager(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Customer manager details',
                        'data' => [
                            'managerDetails' => User::where('created_by', $request->customer_id)->get(),
                            'farm' => CustomerFarm::where('customer_id', $request->customer_id)->get()
                        ]
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getFarmManager(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Customer manager details',
                        'data' => CustomerFarm::where('id', $request->farm_id)->with('farmManager')->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

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
        $farms = CustomerFarm::where('customer_id', $customer->id)->get();
        $totalFarms = $farms->count();
        $jobs = Job::where('customer_id', $customer->id)
                ->select('id', 'service_id', 'farm_id', 'job_providing_date', 'time_slots_id', 'notes', 'amount', 'job_status', 'truck_driver_id', 'skidsteer_driver_id')
                ->with(['farm' => function($q) {
                    $q->select('id', 'farm_address', 'farm_city', 'farm_province', 'farm_unit', 'farm_zipcode');
                }])
                ->with(['truck_driver' => function($q) {
                    $q->select('id', 'first_name');
                }])
                ->with(['skidsteer_driver' => function($q) {
                    $q->select('id', 'first_name');
                }])
                ->with(['service' => function($q) {
                    $q->select('id', 'service_name');
                }])
                ->get();
        $invoices = Job::where('payment_status', config('constant.payment_status.paid'))->select('id', 'customer_id', 'service_id', 'amount', 'payment_mode','job_status','payment_status','quick_book', 'created_at')->with(['customer' => function($q) {
                            $q->select('id', 'first_name', 'last_name', 'email');
                        }])->with(['service' => function($q) {
                            $q->select('id', 'service_name');
                        }])->orderBy('created_at', 'DESC')->get()->append('job_invoice');
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
                        'jobDetails' => $jobs,
                        'invoices' => $invoices
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
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $confirmed = 1;
            $customerDetails = User::whereId($request->customer_id)->first();
            if ($request->email != '' && $request->email != null) {
                if ($customerDetails->email !== $request->email) {
                    $checkEmail = User::where('email', $request->email)->first();
                    if ($checkEmail !== null) {
                        if ($checkEmail->id !== $customerDetails->id) {
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
                    $customerDetails->password = bcrypt($request->password);
                }
                $customerDetails->prefix = (isset($request->prefix) && $request->prefix != '' && $request->prefix != null) ? $request->prefix : null;
                $customerDetails->first_name = $request->customer_first_name;
                $customerDetails->last_name = $request->customer_last_name;
                $customerDetails->email = $request->email;
                $customerDetails->phone = $request->customer_phone;
                $customerDetails->address = $request->customer_address;
                $customerDetails->city = $request->customer_city;
                $customerDetails->state = $request->customer_province;
                $customerDetails->zip_code = $request->customer_zipcode;
                $customerDetails->user_image = (isset($request->customer_image) && $request->customer_image != '' && $request->customer_image != null) ? $request->customer_image : null;
                $customerDetails->is_active = $request->customer_is_active;
                if (isset($confirmed)) {
                    $customerDetails->is_confirmed = $confirmed;
                }

                if ($customerDetails->save()) {
                    DB::commit();
                    if ($confirmed == 0) {
                        $this->_updateEmail($customerDetails, $request->email);
                    }
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
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function updateFarm(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
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
                                'message' => $validator->errors(),
                                'data' => []
                                    ], 422);
                }

                DB::beginTransaction();
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
                    'distance_warehouse' => $this->getDistance($request->latitude, $request->longitude, null, null, 'M', 'warehouse'),
                    'distance_dumping_area' => $this->getDistance($request->latitude, $request->longitude, null, null, 'M', 'dumping')
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
                    'farm_id' => 'required',
                    'manager_first_name' => 'required',
                    'manager_last_name' => 'required',
                    'manager_phone' => 'required',
                    'manager_address' => 'required',
                    'manager_city' => 'required',
                    'manager_province' => 'required',
                    'manager_zipcode' => 'required',
                    'manager_is_active' => 'required',
//                    'manager_card_image' => 'required',
//                    'manager_id_card' => 'required',
//                    'salary' => 'required',
//                    'joining_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $confirmed = 1;
            $manager = User::whereId($request->manager_id)->first();
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
                $manager->farm_id = $request->farm_id;
                if (isset($confirmed)) {
                    $manager->is_confirmed = $confirmed;
                }
                if ($manager->save()) {
                        DB::commit();
                        if ($confirmed == 0) {
                            $this->_updateEmail($manager, $request->email);
                        }
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Manager updated successfully.',
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

    public function updateFarmWManagers(Request $request){
        $validator = Validator::make($request->all(), [
            'farm.farm_id' => 'required',
            'farm.farm_address' => 'required',
            'farm.farm_city' => 'required',
            'farm.farm_province' => 'required',
            'farm.farm_zipcode' => 'required',
            'farm.farm_active' => 'required',
            'farm.latitude' => 'required',
            'farm.longitude' => 'required',
    
            'managers.*.manager_name' => 'required',
            'managers.*.manager_email' => 'required|email',
            'managers.*.manager_phone' => 'required',
            'managers.*.manager_address' => 'required',
            'managers.*.manager_city' => 'required',
            'managers.*.manager_province' => 'required',
            'managers.*.manager_zipcode' => 'required',
            // 'managers.*.manager_id_card' => 'required',
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

            // Update Farm
            CustomerFarm::whereId($request->farm['farm_id'])->update([
                'farm_address' => $request->farm['farm_address'],
                'farm_unit' => (isset($request->farm['farm_unit']) && $request->farm['farm_unit'] != '' && $request->farm['farm_unit'] != null) ? ($request->farm['farm_unit']) : null,
                'farm_city' => $request->farm['farm_city'],
                'farm_province' => $request->farm['farm_province'],
                'farm_zipcode' => $request->farm['farm_zipcode'],
                'farm_image' => (isset($request->farm['farm_images']) && $request->farm['farm_images'] != '' && $request->farm['farm_images'] != null) ? json_encode($request->farm['farm_images']) : null,
                'farm_active' => $request->farm['farm_active'],
                'latitude' => $request->farm['latitude'],
                'longitude' => $request->farm['longitude'],
                'distance_warehouse' => $this->getDistance($request->farm['latitude'], $request->farm['longitude'], null, null, 'M', 'warehouse'),
                'distance_dumping_area' => $this->getDistance($request->farm['latitude'], $request->farm['longitude'], null, null, 'M', 'dumping')
            ]);

            $updatedManagerIds = [];
            foreach ($request->managers as $manager) {
                // Check Email
                $existingObj = null;
                if(array_key_exists('manager_id', $manager)){ $existingObj = User::whereId($manager['manager_id'])->first(); }
                if ($manager['manager_email'] != '' && $manager['manager_email'] != null) {
                    if ($existingObj->email !== $manager['manager_email'] || $existingObj == null) {
                        $checkEmail = User::where('email', $manager['manager_email'])->first();
                        if ($checkEmail !== null) {
                            if ($checkEmail->id !== $manager['manager_id']) {
                                return response()->json([
                                            'status' => false,
                                            'message' => 'Email '.$manager['manager_email'].' is already taken.',
                                            'data' => []
                                                ], 422);
                            }
                        }
                    }
                }
                // Update Existing
                if(isset($manager['manager_id'])){
                    // array_push($updatedManagerIds, $manager['manager_id']);
                    $managerObj = $existingObj;
                    $managerDetailObj = ManagerDetail::where('user_id', $manager['manager_id'])->first();
                    if($managerDetailObj == null){ $managerDetailObj = new ManagerDetail(); }
                }else{
                    $managerObj = new User();
                    $managerDetailObj = new ManagerDetail();
                }
                
                $managerObj->prefix = (isset($manager['manager_prefix']) && $manager['manager_prefix'] != '' && $manager['manager_prefix'] != null) ? $manager['manager_prefix'] : null;
                $managerObj->first_name = $manager['manager_name'];
                $managerObj->email = $manager['manager_email'];
                $managerObj->phone = $manager['manager_phone'];
                $managerObj->address = $manager['manager_address'];
                $managerObj->city = $manager['manager_city'];
                $managerObj->state = $manager['manager_province'];
                $managerObj->zip_code = $manager['manager_zipcode'];
                $managerObj->user_image = (isset($manager['manager_image']) && $manager['manager_image'] != '' && $manager['manager_image'] != null) ? $manager['manager_image'] : null;
                $managerObj->farm_id = $request->farm['farm_id'];
                
                if ($managerObj->save()) {
                    array_push($updatedManagerIds, $managerObj->id);
//                    $managerDetailObj->user_id = $managerObj->id;
//                    $managerDetailObj->identification_number = $manager['manager_id_card'];
//                    $managerDetailObj->document = $manager['manager_card_image'] ?? null;
//                    $managerDetailObj->save();
                }
            }
            
            // Deleted removed Managers
            User::where('farm_id', $request->farm['farm_id'])->whereNotIn('id', $updatedManagerIds)->delete();

            DB::commit();
            return response()->json([
                        'status' => true,
                        'message' => 'Farm & Manager details saved successfully.',
                        'data' => []
                            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            // Log::error(json_encode($e->getMessage()));
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

    public function updateCustomerPaymentMode(Request $request) {
        $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'payment_mode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $user = User::where('id', $request->customer_id)->first();

            if ($user->payment_mode != $request->payment_mode) {
                if (User::where('id', $request->customer_id)->update(['payment_mode' => $request->payment_mode])) {
                    $customerActivity = new CustomerActivity([
                        'customer_id' => $request->customer_id,
                        'created_by' => $request->user()->id,
                        'activities' => 'Payment mode is changed to '.config('constant.payment_mode_inverse.'.$request->payment_mode).' from wellington office by ' . $request->user()->first_name,
                    ]);
                    if ($customerActivity->save()) {
                        // Notification is required.
                        DB::commit();
                    }
                    return response()->json([
                                'status' => true,
                                'message' => 'Payment mode update sucessfully.',
                                'data' => []
                                    ], 200);
                }
                return response()->json([
                            'status' => false,
                            'message' => 'Error while update payment mode.',
                            'data' => []
                                ], 500);
            }

            return response()->json([
                        'status' => false,
                        'message' => 'You entered wrong data.',
                        'data' => []
                            ], 500);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function resetCustomerPassword(User $cid){
        try{
            $newPassword = Str::random();
            $cid->update(['password' => bcrypt($newPassword)]);
            Mail::send('email_templates.reset_password', ['user' => $cid, 'password' => $newPassword], function ($message) use ($cid) {
                $message->to($cid->email, $cid->first_name)->subject('New Password');
                $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
            });
            return response()->json([
                'status' => true,
                'message' => 'Password reset successfully.',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
