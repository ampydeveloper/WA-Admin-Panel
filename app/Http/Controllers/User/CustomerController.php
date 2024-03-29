<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

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

class CustomerController extends Controller
{
    
    public function dashboard(Request $request) {
        dd('dashboard');
    }

    /**
     * create customer farm
     */
    public function createFarm(Request $request) {
//        dd($request->all());
//        
//        foreach ($request->manager_details as $manager) {
//            
//        }
        $validator = Validator::make($request->all(), [
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
            $customer = $request->user();
            if ($customer->role_id == config('constant.roles.Customer')) {
                DB::beginTransaction();
                $newPassword = Str::random();
                $farmDetails = new CustomerFarm([
                    'customer_id' => $customer->id,
                    'farm_address' => $request->farm_address,
                    'farm_unit' => (isset($request->farm_unit) && $request->farm_unit != '' && $request->farm_unit != null) ? ($request->farm_unit) : null,
                    'farm_city' => $request->farm_city,
                    'farm_province' => $request->farm_province,
                    'farm_zipcode' => $request->farm_zipcode,
                    'farm_image' => (isset($request->farm_images) && $request->farm_images != '' && $request->farm_images != null) ? json_encode($request->farm_images) : null,
                    'farm_active' => $request->farm_active,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'created_by' => $customer->id,
                ]);
                if ($farmDetails->save()) {
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
                            'created_from_id' => $customer->id,
                            'is_confirmed' => 1,
                            'is_active' => 1,
                            'created_by' => $customer->id,
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
//                                $this->_confirmPassword($saveManger, $newPassword);
                            }
                        }
                    }
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Customer created successfully.',
                                'data' => $saveManger
                                    ], 200);
                }
            } else {
                return response()->json([
                        'status' => false,
                        'message' => 'You cannot create farm.',
                        'data' => []
                            ], 500);
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
    
    /**
     * get customer farm
     */
    public function getFarms(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer farms details',
                    'data' => CustomerFarm::where('customer_id', $request->user()->id)->get()
                        ], 200);
    }
    
    public function getSingleFarm(Request $request, $farmId) {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer farms details',
                    'data' => CustomerFarm::where('id', $farmId)->first()
                        ], 200);
    }
    
    public function deleteFarm(Request $request, $farmId) {
        try {
            CustomerFarm::whereId($farmId)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'Farm deleted Successfully',
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
     * create customer manager
     */
    public function createManager(Request $request) {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
                    'farm_id' => 'required',
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
                'created_by' => $request->user()->id,
                'farm_id' => $request->farm_id,
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
//                    $this->_confirmPassword($saveManager, $newPassword);
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
    
    public function managerLists(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer manager details',
                    'data' => [
                        'managerList' => User::where('created_by', $request->user()->id)->get(),
                    ]
                        ], 200);
    }
    
    public function getSingleManager(Request $request, $managerId) {
        return response()->json([
                    'status' => true,
                    'message' => 'Customer manager details',
                    'data' => [
                        'managerList' => User::whereId($managerId)->first(),
                    ]
                        ], 200);
    }
    
    public function deleteManager(Request $request, $managerId) {
        try {
            User::whereId($managerId)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'Manager deleted Successfully',
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
    
    public function editProfile(Request $request) {
        $validator = Validator::make($request->all(), [
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
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $customerDetails = User::whereId($request->user()->id)->first();
            if ($request->email != '' && $request->email != null) {
                $checkEmail = User::where('email', $request->email)->first();
                if($checkEmail !== null) {
                    if($checkEmail->id !== $customerDetails->id) {
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
                $customerDetails->password = bcrypt($request->password);
            }
            $customerDetails->prefix = (isset($request->customer_prefix) && $request->customer_prefix != '' && $request->customer_prefix != null) ? $request->customer_prefix : null;
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
    
    public function getAllCard(Request $request) {
        $user = User::whereId($request->user()->id)->first();
        if($user->created_by == null) {
            $customerId = $user->id;
        } else {
            $customerId = $user->created_by;
        }
        $card = CustomerCardDetail::where('customer_id', $customerId)->get();
        return response()->json([
                    'status' => true,
                    'message' => 'Card List successfully.',
                    'data' => $card
                        ], 200);
    }
    
    public function getAllRecords(Request $request) {
        $user = User::whereId($request->user()->id)->first();
        if($user->created_by == null) {
            $customerId = $user->id;
        } else {
            $customerId = $user->created_by;
        }
            $customer = User::where('id', $customerId)->first();
        $memberSince = $customer->created_at;
        $farms = CustomerFarm::where('customer_id', $customerId)->get();
        $totalFarms = $farms->count();
        $jobs = Job::where('customer_id', $customerId)->with(['farm', 'truck_driver', 'skidsteer_driver', 'service'])->get();
        $totalJobs = $jobs->count();
        $lifetimeBilling = Payment::where('customer_id', $customerId)->sum('amount');
        $last12MonthBilling = Payment::where('customer_id', $customerId)->whereMonth(
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
    
    public function getPrimaryCard(Request $request) {
        $user = User::whereId($request->user()->id)->first();
        if($user->created_by == null) {
            $customerId = $user->id;
        } else {
            $customerId = $user->created_by;
        }
        $card = CustomerCardDetail::where('customer_id', $customerId)->where('card_primary', 1)->get();
        return response()->json([
                    'status' => true,
                    'message' => 'Card List successfully.',
                    'data' => $card
                        ], 200);
    }
    
    public function addCard(Request $request) {
        $validator = Validator::make($request->all(), [
            'card_id' => 'required|string',
                    'card_number' => 'required|string',
                    'card_exp_month' => 'required',
                    'card_exp_year' => 'required',
                    'card_status' => 'required',
                    'card_primary' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $user = $request->user();
        if ($user->created_by == null) {
            $customerId = $user->id;
        } else {
            $customerId = $user->created_by;
        }
        $checkUserCards = CustomerCardDetail::where('customer_id', $customerId)->get();
        If(count($checkUserCards) <= 0) {
            $primaryCard = 1;
        } else {
            if($request->card_primary == 1) {
                CustomerCardDetail::where('customer_id', $customerId)->where('card_primary', 1)->update(['card_primary' => 0]);
                $primaryCard = 1;
            }
            $primaryCard = 0;
        }
        try {
            DB::beginTransaction();
            $saveCard = new CustomerCardDetail([
                'customer_id' => $customerId,
                'card_id' => $request->card_id,
                'card_number' => $request->card_number,
                'card_exp_month' => $request->card_exp_month,
                'card_exp_year' => $request->card_exp_year,
                'card_status' => $request->card_status,
                'card_primary' => $request->card_primary,
            ]);
            if ($saveCard->save()) {
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Card added created successfully.',
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
    
    public function changePrimaryCard(Request $request) {
        $validator = Validator::make($request->all(), [
                    'card_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $user = User::whereId($request->user()->id)->first();
        if ($user->created_by == null) {
            $customerId = $user->id;
        } else {
            $customerId = $user->created_by;
        }
        if (CustomerCardDetail::where('customer_id', $customerId)->where('card_primary', 1)->update(['card_primary' => 0])) {

            CustomerCardDetail::where('id', $request->card_id)->update(['card_primary' => 1]);
            return response()->json([
                        'status' => true,
                        'message' => 'Primary card changed sucessfully.',
                        'data' => []
                            ], 200);
        }
    }

}
