<?php

namespace App\Http\Controllers;

use Mail;
use App\Job;
use App\User;
use Validator;
use App\Service;
use App\CustomerFarm;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Str;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Carbon;

class JobsController extends Controller {
    
    public function getAllJob(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'job Details',
                        'data' => [
                            'allJobs' => Job::with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get(),
                            'repeatingJobs' => Job::where('is_repeating_job', config('constant.repeating_job.yes'))->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get()
                        ]
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getAllJobMobile(Request $request) {
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
                        'message' => 'job Details',
                        'data' => [
                            'allJobs' => Job::orderBy('id', 'DESC')->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->skip($request->offset)->take($request->take)->get(),
                        ]
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getRepeatingJobMobile(Request $request) {
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
                        'message' => 'job Details',
                        'data' => [
                            'repeatingJobs' => Job::orderBy('id', 'DESC')->where('is_repeating_job', config('constant.repeating_job.yes'))->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->skip($request->offset)->take($request->take)->get()
                        ]
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function jobFilter(Request $request) {
        if ($request->has('job_status')) {
            $filterJobs = $repeatingJobs = Job::where('job_status', $request->job_status)->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get();
        } elseif ($request->has('payment_mode')) {
            $filterJobs = $repeatingJobs = Job::where('payment_mode', $request->payment_mode)->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get();
        } elseif ($request->has('payment_status')) {
            $filterJobs = $repeatingJobs = Job::where('payment_status', $request->payment_status)->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get();
        } elseif ($request->has('quick_book')) {
            $filterJobs = $repeatingJobs = Job::where('quick_book', $request->quick_book)->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get();
        }
        return response()->json([
                    'status' => true,
                    'message' => 'job Details',
                    'data' => [
                        'filterJobs' => $filterJobs,
                    ]
                        ], 200);
    }
    
    public function createJob(Request $request) {

        $validator = Validator::make($request->all(), [
                    'customer_id' => 'required',
                    'manager_id' => 'required',
                    'service_id' => 'required',
                    'job_providing_date' => 'required',
                    'is_repeating_job' => 'required',
                    'payment_mode' => 'required',
                    'repeating_days' => 'required_if:is_repeating_job,==,true',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $checkService = Service::where('id', $request->service_id)->first();
            if ($checkService->service_for == config('constant.roles.Customer')) {

                if ((!isset($request->farm_id) && $request->farm_id == null && $request->farm_id == '')) {
                    return response()->json([
                                'status' => false,
                                'message' => 'The given data was invalid.',
                                'data' => []
                                    ], 422);
                }
            }
            
            if(User::whereId($request->customer_id)->first('role_id')['role_id'] == config('constant.roles.Customer')){
                $timeSlot = null;
                if($request->has('time_slots_id')){ $timeSlot = $request->time_slots_id; }
                if(!$this->__checkAvailability($request->service_id, $request->job_providing_date, $timeSlot)){
                    return response()->json([
                            'status' => false,
                            'message' => 'No slots left for selected service in selected date and service time, please try selecting different options',
                            'data' => []
                                ], 422);
                }
            }
            if ($checkService->service_type == config('constant.service_type.by_weight')) {
                $amount = $checkService->price * $request->amount;
            } else {
                $amount = $checkService->price;
            }
            try {
                $job = new Job([
                    'job_created_by' => $request->user()->id,
                    'customer_id' => $request->customer_id,
                    'manager_id' => (isset($request->manager_id) && $request->manager_id != '' && $request->manager_id != null) ? $request->manager_id : null,
                    'farm_id' => (isset($request->farm_id) && $request->farm_id != '' && $request->farm_id != null) ? $request->farm_id : null,
                    'service_id' => $request->service_id,
                    'gate_no' => (isset($request->gate_no) && $request->gate_no != '' && $request->gate_no != null) ? $request->gate_no : null,
                    'time_slots_id' => (isset($request->time_slots_id) && $request->time_slots_id != '' && $request->time_slots_id != null) ? $request->time_slots_id : null,
                    'job_providing_date' => $request->job_providing_date,
                    'weight' => (isset($request->weight) && $request->weight != '' && $request->weight != null) ? $request->weight : null,
                    'is_repeating_job' => ($request->is_repeating_job == false) ? 1 : 2,
                    'repeating_days' => (isset($request->repeating_days) && $request->repeating_days != '' && $request->repeating_days != null) ? json_encode($request->repeating_days) : null,
                    'payment_mode' => $request->payment_mode,
                    'images' => (isset($request->images) && $request->images != '' && $request->images != null) ? json_encode($request->images) : null,
                    'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
                    'amount' => $amount,
                ]);
                if ($job->save()) {
                    $mailData = [
                        'job_id' => $job->id,
                        'customer_id' => $request->customer_id,
                        'manager_id' => isset($request->manager_id) ? $request->manager_id : null
                    ];
                    $this->_sendPaymentEmail($mailData);
                    return response()->json([
                                'status' => true,
                                'message' => 'Job created successfully.',
                                'data' => []
                                    ], 200);
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

    public function _sendPaymentEmail($mailData) {
        $customerDetails = User::whereId($mailData['customer_id'])->first();
        $customerName = $customerDetails->first_name . ' ' . $customerDetails->last_name;
        $data = array(
            'user' => $customerDetails,
            'name' => $customerName,
        );
        //send to customer
        Mail::send('email_templates.payment_email', $data, function ($message) use ($customerDetails, $customerName) {
            $message->to($customerDetails->email, $customerName)->subject('Job Created');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
        //send to manager
        if ($mailData['manager_id'] !== null) {
            $managerDetails = User::whereId($mailData['manager_id'])->first();
            $managerName = $managerDetails->first_name . ' ' . $managerDetails->last_name;
            $data = array(
                'user' => $managerDetails,
                'name' => $managerName,
            );
            Mail::send('email_templates.payment_email', $data, function ($message) use ($managerDetails, $managerName) {
                $message->to($managerDetails->email, $managerName)->subject('Job Created');
                $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
            });
        }
    }
    /**
     * get customers and hauler
     */
    public function getCustomers(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Customers Details',
                        'data' => User::whereRoleId(config('constant.roles.Customer'))
                                ->orWhere('role_id', config('constant.roles.Haulers'))
                                ->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getJobFrams(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Farm details',
                        'data' => CustomerFarm::where('customer_id', $request->customer_id)->where('farm_active', '1')->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getJobFramManagers(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Farm details',
                        'data' => User::where('farm_id', $request->farm_id)->where('is_confirmed', '1')->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getHaulerDrivers(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Driver List',
                        'data' => User::where('role_id', config('constant.roles.Hauler_driver'))->where('created_by', $request->hauler_id)->where('is_confirmed', '1')->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function getServiceForCustomer(Request $request) {
        $customer = User::whereId($request->customer_id)->first();
        if($customer->role_id == config('constant.roles.Customer') || $customer->role_id == config('constant.roles.Customer_Manager')) {
            $service = Service::where('service_for',config('constant.roles.Customer'))->get();
        } else {
            $service = Service::where('service_for',config('constant.roles.Haulers'))->get();
        }
        return response()->json([
                        'status' => true,
                        'message' => 'Service list',
                        'data' => $service
                            ], 200);
    }

    public function getSingleJob(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $getSingleJobs = Job::whereId($request->job_id)->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->first();
            return response()->json([
                        'status' => true,
                        'message' => 'single job Details',
                        'data' => $getSingleJobs
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    /**
     * get job
     */
//    public function fetchJobDetails(Request $request)
//    {
//        $loadJob = Job::whereId(base64_decode($request->unique_job_id))->first();
//        if ($loadJob != null) {
//            $message = "Job details!";
//            $data = $loadJob;
//        } else {
//            $message = "Job not found!";
//            $data = [];
//        }
//        return response()->json([
//            'status' => true,
//            'message' => $message,
//            'data' => $data
//        ], 200);
//    }
    
    public function updateBookedJob(Request $request) {
        $validator = Validator::make($request->all(), [
                    'job_id' => 'required',
                    'service_id' => 'required',
                    'job_providing_date' => 'required',
                    'is_repeating_job' => 'required',
                    'payment_mode' => 'required',
                    'amount' => 'required',
                    'repeating_days' => 'required_if:is_repeating_job,==,2',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }

        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $checkService = Service::where('id', $request->service_id)->first();
            if ($checkService->service_for == config('constant.roles.Customer')) {
                if ((isset($request->manager_id) && $request->manager_id == null && $request->manager_id == '') || (isset($request->farm_id) && $request->farm_id == null && $request->farm_id == '') || (isset($request->time_slots_id) && $request->time_slots_id == null && $request->time_slots_id == '')) {
                    return response()->json([
                                'status' => false,
                                'message' => 'The given data was invalid.',
                                'data' => []
                                    ], 422);
                }
            }
            $checkIfEdittingAllowed = Job::where('id', $request->job_id)->first();
            if (round((strtotime($checkIfEdittingAllowed->job_providing_date) - strtotime(date('Y/m/d'))) / 3600, 1)) {
                try {
                    Job::whereId($request->job_id)->update([
                        'manager_id' => (isset($request->manager_id) && $request->manager_id != '' && $request->manager_id != null) ? $request->manager_id : null,
                        'farm_id' => (isset($request->farm_id) && $request->farm_id != '' && $request->farm_id != null) ? $request->farm_id : null,
                        'gate_no' => (isset($request->gate_no) && $request->gate_no != '' && $request->gate_no != null) ? $request->gate_no : null,
                        'service_id' => $request->service_id,
                        'time_slots_id' => (isset($request->time_slots_id) && $request->time_slots_id != '' && $request->time_slots_id != null) ? $request->time_slots_id : null,
                        'job_providing_date' => $request->job_providing_date,
                        'weight' => (isset($request->weight) && $request->weight != '' && $request->weight != null) ? $request->weight : null,
                        'is_repeating_job' => $request->is_repeating_job,
                        'repeating_days' => (isset($request->repeating_days) && $request->repeating_days != '' && $request->repeating_days != null) ? $request->repeating_days : null,
                        'payment_mode' => $request->payment_mode,
                        'images' => (isset($request->images) && $request->images != '' && $request->images != null) ? $request->images : null,
                        'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
                        'amount' => $request->amount,
                    ]);
                    $mailData = [
                        'job_id' => $request->job_id,
                        'customer_id' => $checkIfEdittingAllowed->customer_id,
                        'manager_id' => isset($request->manager_id) ? $request->manager_id : null
                    ];
                    $this->_sendPaymentEmail($mailData);
                    return response()->json([
                                'status' => true,
                                'message' => 'Job created successfully.',
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
                        'message' => 'You cannot cancel the job.',
                        'data' => []
                            ], 500);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function cancelJob(Request $request) {
        $validator = Validator::make($request->all(), [
                    'job_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            $bookedService = Job::where('id', $request->job_id)->first();
            if ($bookedService->job_status == config('constant.job_status.open')) {
                try {
                    Job::whereId($request->job_id)->update(['job_status' => config('constant.job_status.cancelled')]);
                    Job::whereId($request->job_id)->delete();
                    return response()->json([
                                'status' => true,
                                'message' => 'Job deleted successfully',
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
                        'message' => 'You cannot cancel the job.',
                        'data' => []
                            ], 500);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function dispatches(Request $request) {
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Dispatches',
                        'data' => Job::where('job_status', config('constant.job_status.assigned'))->where('job_providing_date', date("Y-m-d"))->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

    public function updateDriverVehicle($job_id, $type, $id){
        $fieldMapping = ['truckDriver' => 'truck_driver_id', 'truck' => 'truck_id', 'skidsteerDriver' => 'skidsteer_driver_id', 'skidsteer' => 'skidsteer_id'];
        try{
            if(Job::where('id', $job_id)->update([$fieldMapping[$type] => $id])){
                return response()->json([
                                'status' => true,
                                'message' => $type.' updated successfully',
                                'data' => []
                                    ], 200);
            }
            return response()->json([
                    'status' => false,
                    'message' => $type.' cannot be updated, please contact support!',
                    'data' => []
                        ], 500);
        }catch (\Exception $e) {
            dd($e);
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }

    private function __checkAvailability($service=null, $date=null, $timeSlot=null, $weight=false){
        // Assume Available Minutes per slot 180
        // $service = 20; //test
        // $date = '2020-11-25'; //test
        // $timeSlot = 3; //test

        // // Job by weight //Discussion Pending
        // // Get weight from job
        
        // Available Trucks & Drivers
        $drivers = User::join('drivers', 'drivers.user_id', '=', 'users.id')->where(['users.role_id' => config('constant.roles.Driver'), 'users.is_active' => 1, 'drivers.driver_type' => config('constant.driver_type.truck_driver'), 'drivers.status' => config('constant.driver_status.available')])->count();
        $vehicles = Vehicle::where(['vehicle_type' => config('constant.vehicle_type.truck'), 'status' => config('constant.vehicle_status.available')])->count();
        
        $totalMinutes = ($drivers < $vehicles ? $drivers : $vehicles) * 180;
        
        $ttc_mapping = config('constant.time_taken_to_complete_service_reverse');
        $service_ttc = (int) $ttc_mapping[Service::whereId($service)->first('time_taken_to_complete_service')['time_taken_to_complete_service']];
        
        $bookedJobs = Job::where(['job_providing_date' => $date, 'service_id' => $service]);

        // if($timeSlot != null){ $bookedJobs->where('time_slots_id', $timeSlot); $totalMinutes *= 3; } 
        
        $bookedJobs = $bookedJobs->with('service')->get();
        
        $bookedMinutes = 0;
        foreach($bookedJobs as $job){
            // add distance from warehouse and dumoign area in total, get from farm associated with job, to-do, community
            $bookedMinutes += (int) $ttc_mapping[$job->service->time_taken_to_complete_service] + $job->farm->distance_warehouse + $job->farm->distance_dumping_area; //ttc + commute distance from warehouse and dumping_area
        }
        $availableMinutes = $totalMinutes - $bookedMinutes;

        return $service_ttc <= $availableMinutes ? True : False;
    }

}
