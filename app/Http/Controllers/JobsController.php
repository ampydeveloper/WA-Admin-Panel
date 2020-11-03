<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Validator;
use Mail;
use App\User;
use App\Service;
use App\Job;
use App\CustomerFarm;

class JobsController extends Controller {
    /**
     * get all jobs
     */
    public function getAllJob(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'job Details',
                    'data' => [
                        'allJobs' => Job::with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get(),
                        'repeatingJobs' => Job::where('is_repeating_job', config('constant.repeating_job.yes'))->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get()
                    ]
                        ], 200);
    }
    public function getAllJobMobile(Request $request) {
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
                    'message' => 'job Details',
                    'data' => [
                        'allJobs' => Job::orderBy('id', 'DESC')->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->skip($request->offset)->take($request->take)->get(),
                    ]
                        ], 200);
    }
    
    public function getRepeatingJobMobile(Request $request) {
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
                    'message' => 'job Details',
                    'data' => [
                        'repeatingJobs' => Job::orderBy('id', 'DESC')->where('is_repeating_job', config('constant.repeating_job.yes'))->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->skip($request->offset)->take($request->take)->get()
                    ]
                        ], 200);
    }
    /**
     * filter jobs
     */
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
    /**
     * create job
     */
    public function createJob(Request $request) {
//        dump($request->all());
//        dd($request->is_repeating_job == 'false');
       
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
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $checkService = Service::where('id', $request->service_id)->first();
        if ($checkService->service_for == config('constant.roles.Customer')) {
            
            if ((!isset($request->farm_id) && $request->farm_id == null && $request->farm_id == '') || (!isset($request->time_slots_id) && $request->time_slots_id == null && $request->time_slots_id == '')) {
                return response()->json([
                            'status' => false,
                            'message' => 'The given data was invalid.',
                            'data' => []
                                ], 422);
            }
        }
        if($checkService->service_type == config('constant.service_type.by_weight')) {
            $amount = $checkService->price*$request->amount;
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
    }
    /**
     * Job booking email
     */
    public function _sendPaymentEmail($mailData) {
//        dd($mailData);
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
    public function getCustomers() {
        return response()->json([
                    'status' => true,
                    'message' => 'Customers Details',
                    'data' => User::whereRoleId(config('constant.roles.Customer'))
                            ->orWhere('role_id', config('constant.roles.Haulers'))
                            ->get()
                        ], 200);
    }
    /**
     * get farms
     */
    public function getJobFrams(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Farm details',
                    'data' => CustomerFarm::where('customer_id', $request->customer_id)->where('farm_active', '1')->get()
                        ], 200);
    }
    
    public function getJobFramManagers(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Farm details',
                    'data' => User::where('farm_id', $request->farm_id)->where('is_confirmed', '1')->get()
                        ], 200);
    }
    /**
     * get service time slots
     */
//    public function getServiceSlots(Request $request) {
//        $service = Service::whereId($request->service_id)->first();
//        if ($service != null) {
//            $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get();
//            return response()->json([
//                        'status' => true,
//                        'message' => 'Service Slot Details',
//                        'data' => $timeSlots
//                            ], 200);
//        } else {
//            return response()->json([
//                        'status' => true,
//                        'message' => 'No time slot available',
//                        'data' => []
//                            ], 500);
//        }
//    }
    /**
     * get single jobs
     */
    public function getSingleJob(Request $request) {
        $getSingleJobs = Job::whereId($request->job_id)->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->first();
        return response()->json([
                    'status' => true,
                    'message' => 'single job Details',
                    'data' => $getSingleJobs
                        ], 200);
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
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
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
//        dd($checkIfEdittingAllowed->toArray());
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
    }

    public function cancelJob(Request $request) {
        $validator = Validator::make($request->all(), [
                    'job_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $bookedService = Job::where('id', $request->job_id)->first();
        if (round((strtotime($bookedService->job_providing_date) - strtotime(date('Y/m/d'))) / 3600, 1) >= 24) {
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
    }
    
    
    public function dispatches() {
        return response()->json([
                    'status' => true,
                    'message' => 'Dispatches',
                    'data' => Job::where('job_status', config('constant.job_status.assigned'))->where('job_providing_date', date("Y-m-d"))->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get()
                        ], 200);
    }

}
