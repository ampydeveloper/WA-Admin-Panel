<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;

use Mail;
use Storage;
use App\Job;
use App\User;
use App\Driver;
use App\Payment;
use App\Service;
use App\TimeSlots;
use Carbon\Carbon;
use App\CustomerFarm;
use App\ManagerDetail;
use App\ServicesTimeSlot;
use Illuminate\Support\Str;
use App\CustomerCardDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller {
    
    public function getDriver(Request $request) {
        
        $driver = $request->user();
        if($driver->role_id != config('constant.roles.Driver')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
        }
        return response()->json([
                            'status' => true,
                            'message' => 'Driver details.',
                            'data' => User::whereId($request->user()->id)->with('driver')->first()
                                ], 200);
    }
    
    
    public function editProfile(Request $request) {
        
        $driver = $request->user();
        if($driver->role_id != config('constant.roles.Driver')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
        }
        
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|email',
                    'image' => 'sometimes|required',
                    'phone' => 'required|numeric',
                    'address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'zipcode' => 'required|numeric',
                    'is_active' => 'required',
                    'licence_no' => 'required',
                    'expiry_date' => 'required',
                    'licence_image' => 'sometimes|required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        
            if ($request->email != '' && $request->email != null) {
                if ($driver->email != $request->email) {
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
            $checkDriverLicence = Driver::where('driver_licence', $request->licence_no)->first();
            $driverDetail = $driver->driver;
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
            $driver->first_name = $request->first_name;
            $driver->last_name = $request->last_name;
            $driver->email = $request->email;
            $driver->phone = $request->phone;
            $driver->address = $request->address;
            $driver->city = $request->city;
            $driver->state = $request->province;
            $driver->zip_code = $request->zipcode;
            $driver->is_active = $request->is_active;
            if(isset($confirmed)) {
                $driver->is_confirmed = $confirmed;
            }
            if (is_file($request->image)) {
                $file = "";
                $path = public_path() . '/uploads';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $cover = $request->file('image');
                $extension = $cover->getClientOriginalExtension();
                $file = $cover->getFilename() . '.' . $extension;
                if (!Storage::disk('public')->put($cover->getFilename() . '.' . $extension, File::get($cover))) {
                    $file = "";
                } else {
                    $file = 'uploads/' . $file;
                }
                $driver->user_image = $file;
            }
            $driverDetails = Driver::where('user_id', $driver->id)->first();
            $driverDetails->expiry_date = $request->expiry_date;
            $driverDetails->driver_licence = $request->licence_no;
            if (is_file($request->licence_image)) {
                
                $file = "";
                $path = public_path() . '/uploads';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $cover = $request->file('licence_image');
                $extension = $cover->getClientOriginalExtension();
                $file = $cover->getFilename() . '.' . $extension;
                if (!Storage::disk('public')->put($cover->getFilename() . '.' . $extension, File::get($cover))) {
                    $file = "";
                } else {
                    $file = 'uploads/' . $file;
                }
                $driverDetails->document = $file;
            }
            
            if ($driver->save() && $driverDetails->save()) {
                DB::commit();
                if (isset($confirmed)) {
                    $this->_updateEmail($driver, $request->email);
                        $request->user()->token()->revoke();
                        return response()->json([
                                    'status' => true,
                                    'message' => 'Successfully logged out',
                                    'data' => []
                        ]);
                }
                unset($driver['driver']);
                $driver['driverDetails'] = $driverDetails;
                return response()->json([
                            'status' => true,
                            'message' => 'Driver updated successfully.',
                            'data' => $driver
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
    
    public function deliveredJobs(Request $request) {
        $driver = $request->user();
        if ($driver->role_id != config('constant.roles.Driver')) {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                        'data' => []
                            ], 421);
        }

        return response()->json([
                    'status' => true,
                    'message' => 'delivered jobs.',
                    'data' => Job::whereIn('job_status', [config('constant.job_status.completed'), config('constant.job_status.close')])->where(function($q) use($driver) {
                                $q->where('truck_driver_id', $driver->id)->orWhere('skidsteer_driver_id', $driver->id);
                            })->with("customer", "manager", "farm", "service")->get()
                        ], 200);
    }

    public function ongoingJobs(Request $request) {
        $driver = $request->user();
        if ($driver->role_id != config('constant.roles.Driver')) {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                        'data' => []
                            ], 421);
        }
        return response()->json([
                    'status' => true,
                    'message' => 'ongoing jobs.',
                    'data' => Job::where('job_status', config('constant.job_status.assigned'))->where(function($q) use($driver) {
                                $q->where('truck_driver_id', $driver->id)->orWhere('skidsteer_driver_id', $driver->id);
                            })->with("customer", "manager", "farm", "service")->get()
                        ], 200);
    }

    public function jobDetail(Request $request) {
        $driver = $request->user();
        if($driver->role_id != config('constant.roles.Driver')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
        }
        
        $data = Job::whereId($request->job_id)->with("customer", "manager", "farm", "service", 'truck', 'skidsteer', 'truck_driver', 'skidsteer_driver')->first();
        $jobDetails['id'] = $data->id;
        $jobDetails['service_name'] = $data->service->service_name;
        $jobDetails['customer_name'] = $data->customer->first_name." ". $data->customer->last_name;
        $jobDetails['manager_name'] = $data->manager->first_name;
        $jobDetails['manager_email'] = $data->manager->email;
        $jobDetails['manager_phone_no'] = $data->manager->phone;
        $jobDetails['farm_location'] = $data->farm->farm_unit." ".$data->farm->farm_address." ".$data->farm->farm_city." ".$data->farm->farm_province." ".$data->farm->farm_zipcode;
        $jobDetails['start_time'] = $data->start_time;
        $jobDetails['end_time'] = $data->end_time;
        $jobDetails['truck_no'] = $data->truck->truck_number;
        $jobDetails['skidsteer_no'] = $data->skidsteer->truck_number;
                
        if($driver->id == $data->truck_driver->id) {
            $jobDetails['skidsteer_driver_name'] = $data->skidsteer_driver->first_name;
        } else {
            $jobDetails['truck_driver_name'] = $data->truck_driver->first_name;
        }
        return response()->json([
                            'status' => true,
                            'message' => 'job details.',
                            'data' => $jobDetails
                                ], 200);
        
    }
    
    public function startJob(Request $request) {
        $driver = $request->user();
        if ($driver->role_id != config('constant.roles.Driver')) {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                        'data' => []
                            ], 421);
        }
        
        $validator = Validator::make($request->all(), [
                    'job_id' => 'required',
                    'starting_miles' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        
        try {
            $data = Job::where('id', $request->job_id)->where('job_status', config('constant.job_status.assigned'))->first();
            $data->update(['start_time' => date("h:i:s A", time()),'starting_miles' => $request->starting_miles]);
            return response()->json([
                            'status' => true,
                            'message' => 'job started sucessfully.',
                            'data' => $data
                                ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    
    public function endJob(Request $request) {
        $driver = $request->user();
        if ($driver->role_id != config('constant.roles.Driver')) {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                        'data' => []
                            ], 421);
        }
        
        $validator = Validator::make($request->all(), [
                    'job_id' => 'required',
                    'ending_miles' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        
        try {
            
            $data = Job::where('id', $request->job_id)->where('job_status', config('constant.job_status.assigned'))->first();
            if($request->ending_miles > $data->starting_miles) {
                $data->update(['end_time' => date("h:i:s A", time()), 'ending_miles' => $request->ending_miles, 'job_status' => config('constant.job_status.completed')]);
                return response()->json([
                            'status' => true,
                            'message' => 'job ended sucessfully.',
                            'data' => $data
                                ], 200);
            } else {
                return response()->json([
                            'status' => false,
                            'message' => 'You entered ending miles less then starting miles',
                            'data' => []
                                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    
    public function earnings(Request $request) {
        $driver = $request->user();
        if ($driver->role_id != config('constant.roles.Driver')) {
            return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized access.',
                        'data' => []
                            ], 421);
        }
        $driverDetails = $driver->driver;
//        dd($driverDetails->toArray());
        $first_day_this_month = date('Y-m-01'); 
        $last_day_this_month  = date('Y-m-t');
        dump($first_day_this_month);
        dump($last_day_this_month);
        $jobs = Job::where('truck_driver_id', $driver->id)->where(function($q) {
                $q->where('job_status', config('constant.job_status.completed'))->orWhere('job_status', config('constant.job_status.close'));
            })->get();
            
        if($driverDetails->salary_type == config('constant.salary_type.per_month')) {
            $data['total_amount'] = $driverDetails->driver_salary;
            
            dump($jobs->toArray());
            if($jobs->count()) {
                foreach($jobs as $key=>$job) {
                $data[$key]['date'] = $job->job_providing_date;
                $data[$key]['total_mile'] = $job->ending_miles - $job->starting_miles;
                $data[$key]['total_time'] = $job->ending_miles - $job->starting_miles;
                $data[$key]['shift_time'] = $job->ending_miles - $job->starting_miles;
                    
                }
            }
            return response()->json([
                            'status' => true,
                            'message' => 'job ended sucessfully.',
                            'data' => $data
                                ], 200);
            
        } else {
            
        }
    }
    
    public function routes(Request $request) {
        dd('routes');
    }
    
    public function startRoute(Request $request) {
        dd('routes');
    }
    
    public function endRoute(Request $request) {
        dd('endRoute');
    }
    
    public function jobFilter(Request $request) {
        dd('jobFilter');
    }

}
