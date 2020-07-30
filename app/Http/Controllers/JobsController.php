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
use App\TimeSlots;
use App\Job;
use App\CustomerFarm;

class JobsController extends Controller
{

    /**
     * get customers and company
     */
    public function getCustomers()
    {
        return response()->json([
            'status' => true,
            'message' => 'Manager Details',
            'data' => User::select('id', 'first_name', 'last_name', 'prefix')
                ->whereRoleId(config('constant.roles.Customer'))
                ->orWhere('role_id', config('constant.roles.Company'))
                ->get()
        ], 200);
    }
    /**
     * get farms
     */
    public function getJobFrams(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Customer Details',
            'data' => CustomerFarm::where('customer_id', $request->customer_id)->where('manager_id', $request->manager_id)->where('farm_active', '1')->first()
        ], 200);
    }

    /**
     * get service time slots
     */
    public function getServiceSlots(Request $request)
    {
        $service = Service::whereId($request->service_id)->first();
        //get timeSlots
        $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get();

        return response()->json([
            'status' => true,
            'message' => 'Manager Details',
            'data' => $timeSlots
        ], 200);
    }

    /**
     * create job
     */
    public function createJob(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'job_amount' => 'required',
            'customer_id' => 'required',
            'manager_id' => 'required',
            'farm_id' => 'required',
            'job_description' => 'required',
            'gate_no' => 'required',
            'service_id' => 'required',
            'start_date' => 'required',
            'time_slots_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {

            //create job
            $job = new Job([
                'job_amount' => $request->job_weight != "" && $request->job_weight != null ? $request->job_weight * $request->job_amount : $request->job_amount,
                'customer_id' => $request->customer_id,
                'job_weight' => $request->job_weight,
                'manager_id' => $request->manager_id,
                'farm_id' => $request->farm_id,
                'job_description' => $request->job_description,
                'gate_no' => $request->gate_no,
                'service_id' => $request->service_id,
                'time_slots_id' => $request->time_slots_id,
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
                'job_images' => json_encode($request->job_images)
            ]);
            //save job
            if ($job->save()) {
                $this->_sendPaymentEmail($job->id, $request->customer_id, $request->manager_id);
            }

            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Job created successfully.',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    /**
     * payment email
     */
    /**
     * payment email
     */
    public function _sendPaymentEmail($jobId, $customerId, $managerId)
    {
        //check user role
        $checkRole = User::whereId($customerId)->first();

        $customerDetails = User::whereId($customerId)->first();
        $managerDetails = User::whereId($managerId)->first();

        $customerName = $customerDetails->first_name . ' ' . $customerDetails->last_name;
        $data = array(
            'user' => $customerDetails,
            'name' => $customerName,
            'paymentLink' => env('APP_URL') . 'payment/' . base64_encode($jobId),
            'userRoler' => $checkRole
        );

        //send to customer
        Mail::send('email_templates.payment_email', $data, function ($message) use ($customerDetails, $customerName) {
            $message->to($customerDetails->email, $customerName)->subject('Job Created');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });

        $managerName = $managerDetails->first_name . ' ' . $managerDetails->last_name;
        $data = array(
            'user' => $managerDetails,
            'name' => $managerName,
            'paymentLink' => env('APP_URL') . 'payment/' . base64_encode($jobId),
            'userRoler' => $checkRole
        );

        //send to manager
        Mail::send('email_templates.payment_email', $data, function ($message) use ($managerDetails, $managerName) {
            $message->to($managerDetails->email, $managerName)->subject('Job Created');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
    }

    /**
     * get all jobs
     */
    public function getAllJob(Request $request)
    {
       if($request->status == 'Assigned Jobs'){
         return $this->getAssignedJob();
       }else if($request->status == 'Completed Jobs'){
         return $this->getCompleteJob();
       }else if($request->status == 'Paid'){
           return $this->getPaidJob();
       }else if($request->status == 'Unpaid'){
	return $this->getUnpaidJob();
       }else if($request->status == 'Open'){
	return $this->getOpenJob();
       }else{
        $getAllJobs = Job::with(
            "customer",
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        )->get();

        return response()->json([
            'status' => true,
            'message' => 'job Details',
            'data' => $getAllJobs
        ], 200);
      }
    }

    /**
     * get dispatch jobs
     */
    public function getDispatchJob()
    {
        $getAllJobs = Job::with(
            "customer",
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        )->whereStartDate(Carbon::today())->get();

        return response()->json([
            'status' => true,
            'message' => 'job Details',
            'data' => $getAllJobs
        ], 200);
    }

    /**
     * get assigned job
     */
    public function getAssignedJob()
    {
        $getAllJobs = Job::with(
            "customer",
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        )
            ->whereNotNull("truck_driver_id")
            ->whereNotNull("truck_id")
            ->whereNotNull("skidsteer_id")
            ->whereNotNull("skidsteer_driver_id")
            ->get();
	if($getAllJobs->count()){
        return response()->json([
            'status' => true,
            'message' => 'job Details',
            'data' => $getAllJobs
        ], 200);
	}else{
        return response()->json([
            'status' => false,
            'message' => 'No assigned jobs found.',
            'data' => ''
        ], 200);
	}	
    }

    /**
     * get completed jobs
     */
    public function getCompleteJob()
    {
        $getAllJobs = Job::with(
            "customer",
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        )
            ->whereJobStatus(config('constant.job_status.close'))
            ->get();

    	if($getAllJobs->count()){
        return response()->json([
            'status' => true,
            'message' => 'job Details',
            'data' => $getAllJobs
        ], 200);
	}else{
        return response()->json([
            'status' => false,
            'message' => 'No completed jobs found.',
            'data' => ''
        ], 200);
	}
    }

    /**
     * get open jobs
     */
    public function getOpenJob()
    {
        $getAllJobs = Job::with(
            "customer",
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        )
            ->whereJobStatus(config('constant.job_status.open'))
            ->get();
        	if($getAllJobs->count()){
        return response()->json([
            'status' => true,
            'message' => 'job Details',
            'data' => $getAllJobs
        ], 200);
	}else{
        return response()->json([
            'status' => false,
            'message' => 'No opned job found.',
            'data' => ''
        ], 200);
	}
    }

    /**
     * get repeating jobs
     */
    public function getRepeatingJob()
    {
        $getAllJobs = Job::with(
            "customer",
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        )
            ->whereRepeatingJob(config('constant.repeating_job.true'))
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'job Details',
            'data' => $getAllJobs
        ], 200);
    }

    /**
     * get unpaid jobs
     */
    public function getUnpaidJob()
    {
        $getAllJobs = Job::with(
            "customer",
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        )
            ->wherePaymentStatus(config('constant.payment_history.pending'))
            ->get();

     	if($getAllJobs->count()){
        return response()->json([
            'status' => true,
            'message' => 'job Details',
            'data' => $getAllJobs
        ], 200);
	}else{
        return response()->json([
            'status' => false,
            'message' => 'No unpaid job found.',
            'data' => ''
        ], 200);
	}
    }

    /**
     * get paid jobs
     */
    public function getPaidJob()
    {
        $getAllJobs = Job::with(
            "customer",
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        )
            ->wherePaymentStatus(config('constant.payment_history.complete'))
            ->get();

    	if($getAllJobs->count()){
        return response()->json([
            'status' => true,
            'message' => 'job Details',
            'data' => $getAllJobs
        ], 200);
	}else{
        return response()->json([
            'status' => false,
            'message' => 'No paid jobs found.',
            'data' => ''
        ], 200);
	}
    }

    /**
     * get single jobs
     */
    public function getSingleJob(Request $request)
    {
        $getSingleJobs = Job::with(
            "customer",
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        )
            ->whereId($request->job_id)
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'single job Details',
            'data' => $getSingleJobs
        ], 200);
    }

    /**
     * get job
     */
    public function fetchJobDetails(Request $request)
    {
        $loadJob = Job::whereId(base64_decode($request->unique_job_id))->first();
        if ($loadJob != null) {
            $message = "Job details!";
            $data = $loadJob;
        } else {
            $message = "Job not found!";
            $data = [];
        }
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }
}
