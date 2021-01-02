<?php

namespace App\Http\Controllers;

use Mail;
use App\Job;
use App\User;
use Validator;
use App\Service;
use App\CustomerFarm;
use App\Vehicle;
use App\JobAssignmentHistory;
use App\CustomerActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

            if (User::whereId($request->customer_id)->first('role_id')['role_id'] == config('constant.roles.Customer')) {
                $timeSlot = null;
                if ($request->has('time_slots_id')) {
                    $timeSlot = $request->time_slots_id;
                }
                if (!$this->__checkAvailability($request->service_id, $request->job_providing_date, $timeSlot)) {
                    return response()->json([
                                'status' => false,
                                'message' => 'No slots left for selected service in selected date and service time, please try selecting different options',
                                'data' => []
                                    ], 200);
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

                    $customerActivity = new CustomerActivity([
                        'customer_id' => $request->customer_id,
                        'job_id' => $job->id,
                        'created_by' => $request->user()->id,
                        'activities' => 'Pick is created with pickup id ' . $job->id . ' from wellington office by ' . $request->user()->first_name,
                    ]);
                    if ($customerActivity->save()) {
                        DB::commit();
                    }


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
        if ($customer->role_id == config('constant.roles.Customer') || $customer->role_id == config('constant.roles.Customer_Manager')) {
            $service = Service::where('service_for', config('constant.roles.Customer'))->get();
        } else {
            $service = Service::where('service_for', config('constant.roles.Haulers'))->get();
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
            $job_images = json_decode($getSingleJobs->images);
            foreach ($job_images as $key => $image) {
                if (strpos($image, 'uploads') !== false) {
                    $job_images_new[] = env('APP_URL') . '/' . $image;
                }else{
                    $job_images_new[] = env('CUSTOMER_URL') . '/' . $image;
                }
            }
            $getSingleJobs->images = json_encode($job_images_new);
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
                    $customerActivity = new CustomerActivity([
                        'customer_id' => $request->customer_id,
                        'created_by' => $request->user()->id,
                        'activities' => 'Pick is created with pickup id ' . $job->id . ' from wellington office by ' . $request->user()->first_name,
                    ]);



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

    public function updateDriverVehicle($job_id, $type, $id) {
        $fieldMapping = ['truckDriver' => 'truck_driver_id', 'truck' => 'truck_id', 'skidsteerDriver' => 'skidsteer_driver_id', 'skidsteer' => 'skidsteer_id'];
        try {
            if (Job::where('id', $job_id)->update([$fieldMapping[$type] => $id])) {
                return response()->json([
                            'status' => true,
                            'message' => $type . ' updated successfully',
                            'data' => []
                                ], 200);
            }
            return response()->json([
                        'status' => false,
                        'message' => $type . ' cannot be updated, please contact support!',
                        'data' => []
                            ], 500);
        } catch (\Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }

    private function __checkAvailability($service = null, $date = null, $timeSlot = null, $weight = false) {
        // Assume Available Minutes per slot 180
        // $service = 18; //test
        // $date = '2021-01-12'; //test
        // $timeSlot = 1; //test

        $average_speed = config('constant.average_truck_speed');
        // // Job by weight //Discussion Pending
        // // Get weight from job
        // Available Trucks & Drivers
        $drivers = User::join('drivers', 'drivers.user_id', '=', 'users.id')->where(['users.role_id' => config('constant.roles.Driver'), 'users.is_active' => 1, 'drivers.driver_type' => config('constant.driver_type.truck_driver'), 'drivers.status' => config('constant.driver_status.available')])->count();
        $vehicles = Vehicle::where(['vehicle_type' => config('constant.vehicle_type.truck'), 'status' => config('constant.vehicle_status.available')])->count();

        $totalMinutes = ($drivers < $vehicles ? $drivers : $vehicles) * 180;

        $ttc_mapping = config('constant.time_taken_to_complete_service_reverse');
        $service_ttc = (int) $ttc_mapping[Service::whereId($service)->first('time_taken_to_complete_service')['time_taken_to_complete_service']];

        // Get All booked jobs including repeating ones
        $repeating_day = strtolower(Carbon::createFromFormat('Y-m-d', $date)->dayName);
        $bookedJobs = Job::where(['job_status' => config('constant.job_status.open'), 'job_providing_date' => $date, 'service_id' => $service])->with('service')->get();

        // if($timeSlot != null){ $bookedJobs->where('time_slots_id', $timeSlot); $totalMinutes *= 3; } 
        // Repeating Jobs
        $repeatingJobs = Job::where(['job_status' => config('constant.job_status.open'), 'service_id' => $service, 'is_repeating_job' => config('constant.repeating_job.yes')])->whereJsonContains('repeating_days', [$repeating_day])->with('service')->get();
        $bookedJobs = $bookedJobs->merge($repeatingJobs);

        $bookedMinutes = 0;
        foreach ($bookedJobs as $job) {
            // add distance from warehouse and dumping area in total, get from farm associated with job, to-do, community
            $bookedMinutes += (int) $ttc_mapping[$job->service->time_taken_to_complete_service] + ($job->farm->distance_warehouse / $average_speed) + ($job->farm->distance_dumping_area / $average_speed); //ttc + commute distance from warehouse and dumping_area
        }
        $availableMinutes = $totalMinutes - $bookedMinutes;
        return $service_ttc <= $availableMinutes ? True : False;
    }

    /**
     * Function for assigning one-time and repeatitive jobs based on current date, time_slot and day(for repeatitive)
     * if driver_id and truck_id is null, then will assign available to jobs to available drivers, first run of the day
     */
    public function assignJob($driver_id = null, $truck_id = null) {
        try {
            $currentDate = Carbon::now()->format('Y-m-d');
            $currentDay = strtolower(Carbon::now()->dayName);

            // Get current timeslot id using hour from current time
            $currentHour = (int) Carbon::now()->format('H');
            $current_time_slot_id = 0;
            $timeSlotTimings = config('constant.service_slot_type_timings');
            foreach ($timeSlotTimings as $time => $slot_id) {
                $time = explode('-', $time);
                if ((int) $time[0] <= $currentHour && $currentHour < (int) $time[1]) {
                    $current_time_slot_id = $slot_id;
                    break;
                }
            }
            // $current_time_slot_id=2; //test
            // Get Job list, excluding Non-Repeating
            $nrJobs = Job::where(['job_status' => config('constant.job_status.open'), 'job_providing_date' => $currentDate, 'time_slots_id' => $current_time_slot_id, 'is_repeating_job' => config('constant.repeating_job.no')])->get();

            // Get Repeating Jobs, to be performed on current Day
            $rJobs = Job::where(['job_status' => config('constant.job_status.open'), 'time_slots_id' => $current_time_slot_id, 'is_repeating_job' => config('constant.repeating_job.yes')])->whereJsonContains('repeating_days', [$currentDay])->get();

            // Get Available Truck and drivers
            if ($driver_id == null && $truck_id == null) {
                $drivers = User::join('drivers', 'drivers.user_id', '=', 'users.id')->where(['users.role_id' => config('constant.roles.Driver'), 'users.is_active' => 1, 'drivers.driver_type' => config('constant.driver_type.truck_driver'), 'drivers.status' => config('constant.driver_status.available')])->pluck('users.id')->toArray();
                $vehicles = Vehicle::where(['vehicle_type' => config('constant.vehicle_type.truck'), 'status' => config('constant.vehicle_status.available')])->pluck('id')->toArray();
            } else {
                $drivers = [$driver_id];
                $vehicles = [$truck_id];
            }
            // dd($nrJobs->count(), $rJobs, $drivers, $vehicles);

            $availability = (count($drivers) < count($vehicles) ? count($drivers) : count($vehicles));

            // Assign drivers and vehicles to Non-Repeating jobs
            if ($nrJobs->count() > 0 && $availability > 0) {
                for ($i = 0; $i < $availability; $i++) {
                    $nrJobs[$i]->update(['truck_id' => $vehicles[$i], 'truck_driver_id' => $drivers[$i]]);
                    unset($vehicles[$i], $drivers[$i]);
                    if (count($vehicles) == 0 || count($drivers) == 0) {
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Job assigned successfully.',
                                    'data' => $nrJobs[$i]
                                        ], 200);
                    }
                    unset($nrJobs[$i]);
                    if (count($nrJobs) == 0) {
                        break;
                    }
                }
            }

            $availability = (count($drivers) < count($vehicles) ? count($drivers) : count($vehicles));
            // Check remaining available drivers and vehicles
            if ($availability != 0) {
                $drivers = array_values($drivers);
                $vehicles = array_values($vehicles); //Normalize indexes
                for ($i = 0; $i < $availability; $i++) {
                    try {
                        // Insert existing assignment info to history
                        DB::beginTransaction();
                        JobAssignmentHistory::updateOrCreate(
                                ['job_id' => $rJobs[$i]->id, 'job_providing_date' => $rJobs[$i]->job_providing_date], array_merge(['job_id' => $rJobs[$i]->id], $rJobs[$i]->only('job_providing_date', 'job_providing_time', 'job_status', 'truck_id', 'truck_driver_id', 'skidsteer_id', 'skidsteer_driver_id', 'start_time', 'end_time', 'starting_miles', 'end_miles'))
                        );

                        // Assign new info
                        $rJobs[$i]->update(['truck_id' => $vehicles[$i], 'truck_driver_id' => $drivers[$i]]);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                    }
                    unset($vehicles[$i], $drivers[$i]);
                    if (count($vehicles) == 0 || count($drivers) == 0) {
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Job assigned successfully.',
                                    'data' => $rJobs[$i]
                                        ], 200);
                    }
                    unset($rJobs[$i]);
                    if (count($rJobs) == 0) {
                        break;
                    }
                }
            }

            // dump($nrJobs, $rJobs, $drivers, $vehicles);
            return 'done';
        } catch (\Exception $e) {
            Log::error(['type' => 'assignJob', 'error' => $e->getMessage()]);
            return response()->json([
                        'status' => false,
                        'message' => 'Job cannot be assigned.',
                        'data' => []
                            ], 500);
        }
    }

    public function jobByMap(Request $request) {
        if ($request->range[0] == 'This week') {
            $start_date = date("Y-m-d", strtotime('last sunday'));
            $end_date = date("Y-m-d", strtotime('next saturday'));
        }
        if ($request->range == 'This month') {
            $start_date = date("Y-m-d", strtotime('first day of this month'));
            $end_date = date("Y-m-d", strtotime('last day of this month'));
        }
        if ($request->range == 'This year') {
            $start_date = date("Y-m-d", strtotime('1 january'));
            $end_date = date("Y-m-d", strtotime('last day of this year'));
        }
        if ($request->user()->role_id == config('constant.roles.Admin') || $request->user()->role_id == config('constant.roles.Admin_Manager')) {
            return response()->json([
                        'status' => true,
                        'message' => 'Dispatches',
                        'data' => Job::where('job_status', config('constant.job_status.assigned'))->where('job_providing_date', '>=', $start_date)->where('job_providing_date', '<=', $end_date)->with("customer", "manager", "farm", "service", "truck", "skidsteer", "truck_driver", "skidsteer_driver")->get()
                            ], 200);
        } else {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                            ], 421);
        }
    }

}
