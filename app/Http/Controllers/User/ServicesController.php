<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Service;
use App\TimeSlots;
use App\User;
use App\Job;
use Illuminate\Support\Str;
use Mail;

class ServicesController extends Controller
{
    
    
    /**
     * Get all the services
     */
    public function listServices(Request $request) {
        $getAllServices = Service::where('service_for', $request->user()->role_id)->get();
        foreach($getAllServices as $key => $service) {
                $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get()->groupBy('slot_type');
                $getAllServices[$key]["timeSlots"] = $timeSlots;
        }
        return response()->json([
            'status' => true,
            'message' => 'Service Listing.',
            'data' => $getAllServices
        ], 200);
    }
    
    
    public function bookService(Request $request) {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
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
//        dump($checkService->toArray());
        if ($checkService->service_for == config('constant.roles.Customer')) {
            if ((isset($request->manager_id) && $request->manager_id == null && $request->manager_id == '') || (isset($request->farm_id) && $request->farm_id == null && $request->farm_id == '') || (isset($request->time_slots_id) && $request->time_slots_id == null && $request->time_slots_id == '')) {
                return response()->json([
                            'status' => false,
                            'message' => 'The given data was invalid.',
                            'data' => []
                                ], 422);
            }
        }
//        dd('here');
        $checkUser = $request->user();
        $customerId = ($checkUser->created_by == null) ? $checkUser->id: $checkUser->created_by;
        try {
            $job = new Job([
                'job_created_by' => $checkUser->id,
                'customer_id' => $customerId,
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
            if ($job->save()) {
                $mailData = [
                    'job_id' => $job->id,
                    'customer_id' => $customerId,
                    'manager_id' => isset($request->manager_id) ? $request->manager_id : null
                ];
//                $this->_sendPaymentEmail($mailData);
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
    

    public function _sendPaymentEmail($mailData) {
        $customer = User::whereId($mailData['customer_id'])->first();
        $customerName = $customer->first_name . ' ' . $customer->last_name;

        Mail::send('email_templates.payment_email', function ($message) use ($customer, $customerName) {
            $message->to($customer->email, $customerName)->subject('Job Created');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });

        if ($mailData['manager_id'] !== null) {
            $manager = User::whereId($mailData['manager_id'])->first();
            $managerName = $manager->first_name . ' ' . $manager->last_name;
            //send to manager
            Mail::send('email_templates.payment_email', function ($message) use ($manager, $managerName) {
                $message->to($manager->email, $managerName)->subject('Job Created');
                $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
            });
        }
    }
    
    public function showCustomerServices(Request $request) {
        $user = User::whereId($request->user()->id)->first();
        if($user->created_by == null) {
            $customerId = $user->id;
        } else {
            $customerId = $user->created_by;
        }
            if($bookedServices = Job::where('customer_id', $customerId)->get()) {
                return response()->json([
                        'status' => true,
                        'message' => 'Booked services list',
                        'data' => $bookedServices
                            ], 200);
            } else {
                return response()->json([
                        'status' => false,
                        'message' => 'Internal server error!',
                        'data' => []
                            ], 500);
            }
    }
    
    public function updateBookedService(Request $request) {
//        dd($request->all());
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
        $user = User::whereId($request->user()->id)->first();
        if($user->created_by == null) {
            $customerId = $user->id;
        } else {
            $customerId = $user->created_by;
            $managerId = $user->id;
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
                    'customer_id' => $customerId,
                    'manager_id' => isset($managerId) ? $managerId : null
                ];
//                $this->_sendPaymentEmail($mailData);
                return response()->json([
                            'status' => true,
                            'message' => 'Job updated successfully.',
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
                    'message' => 'You cannot update the job.',
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
        $user = User::whereId($request->user()->id)->first();
        if($user->created_by == null) {
            $customerId = $user->id;
        } else {
            $customerId = $user->created_by;
            $managerId = $user->id;
        }
        $bookedService = Job::where('id', $request->job_id)->first();
        if (round((strtotime($bookedService->job_providing_date) - strtotime(date('Y/m/d'))) / 3600, 1) >= 24) {
            try {
                Job::whereId($request->job_id)->update(['job_status' => config('constant.job_status.cancelled')]);
                Job::whereId($request->job_id)->delete();
                $mailData = [
                    'job_id' => $request->job_id,
                    'customer_id' => $customerId,
                    'manager_id' => isset($managerId) ? $managerId : null
                ];
//                $this->_sendPaymentEmail($mailData);
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
    
    /**
     * filter jobs
     */
    public function jobFilter(Request $request) {
        $user = User::whereId($request->user()->id)->first();
        if($user->created_by == null) {
            $customerId = $user->id;
        } else {
            $customerId = $user->created_by;
        }
        if ($request->has('job_status')) {
            $filterJobs = $repeatingJobs = Job::where('customer_id', $customerId)->where('job_status', $request->job_status)->with("customer", "manager", "farm", "service", "timeslots", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get();
        } elseif ($request->has('payment_mode')) {
            $filterJobs = $repeatingJobs = Job::where('customer_id', $customerId)->where('payment_mode', $request->payment_mode)->with("customer", "manager", "farm", "service", "timeslots", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get();
        } elseif ($request->has('payment_status')) {
            $filterJobs = $repeatingJobs = Job::where('customer_id', $customerId)->where('payment_status', $request->payment_status)->with("customer", "manager", "farm", "service", "timeslots", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get();
        } elseif ($request->has('quick_book')) {
            $filterJobs = $repeatingJobs = Job::where('customer_id', $customerId)->where('quick_book', $request->quick_book)->with("customer", "manager", "farm", "service", "timeslots", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get();
        }
        return response()->json([
                    'status' => true,
                    'message' => 'job Details',
                    'data' => [
                        'filterJobs' => $filterJobs,
                    ]
                        ], 200);
    }
  
}
