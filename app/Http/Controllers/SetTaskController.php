<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\{Vehicle,Driver,User,Job,CustomerFarm};

class SetTaskController extends Controller
{
    /**
     * assign techs
     */

    public function setTaskCronjob(Request $request)
    {

        $getAllJobs = Job::with([
            "customer"=>function($query){
                $query->with("farmlist");
            },
            "manager",
            "farm",
            "service",
            "timeslots",
            "truck",
            "skidsteer",
            "truck_driver",
            "skidsteer_driver"
        ])
        ->whereJobStatus(config('constant.job_status.open'))
        ->orderBy('start_time', 'ASC')
        ->get()
        ->groupBy(function($item) {
            return $item->job_providing_date;
        });

        // dd($getAllJobs);
        foreach($getAllJobs as $key => $job) {
            
            //get all distance from farm
            $farms = CustomerFarm::where('farm_active',1)->pluck('distance','id');
            $sortedFarms = $farms->sort();
            $sortedFarms = $sortedFarms->all();
            
            //check today date
            if(!Carbon::parse($key)->diffInDays(Carbon::now()->format('Y-m-d'))) {
                $a = $getAllJobs[$key]->groupBy("start_time");
                foreach($a as $data) {
                    foreach($data as $item){
                        //check vehicle/driver available or not
                        $truck = Vehicle::whereVehicleType(config('constant.vehicle_type.truck'))
                        ->where('status',config('constant.vehicle_status.available'))
                        ->first();
                        $truckDriver = Driver::with('user')->whereDriverType(1)
                        ->whereStatus(config('constant.driver_status.available'))
                        ->first();
            
                        if($truck && $truckDriver) {
                            //assign truck and driver to job.
                            if(Job::where('id',$item->id)->update(['truck_id'=>$truck->id,'truck_driver_id'=>$truckDriver->id])){
                                //job status updated.
                                Job::where('id',$item->id)->update(['job_status'=>config('constant.job_status.assigned')]);
                                //update truck and driver status to unavailable.
                                Vehicle::where('id',$truck->id)->update(['status'=>config('constant.vehicle_status.unavailable')]);
                                Driver::where('id',$truckDriver->id)->update(['status'=>config('constant.driver_status.unavailable')]);
                                CustomerFarm::where('id',$item->farm->id)->update(['farm_active',2]);
                            }
                        }
                    }
                }
            } else {
            break;
            }
            dd('$item');
            dd('$day');
            
            // dd($job->toArray());
            
            
            // $truck = Vehicle::whereVehicleType(config('constant.vehicle_type.truck'))
            // ->whereStatus(config('constant.vehicle_status.available'))
            // ->first();
            // $truckDriver = Driver::with('user')->whereDriverType(1)
            // ->whereStatus(config('constant.driver_status.available'))
            // ->first();
            // $skidster = Vehicle::whereVehicleType(config('constant.vehicle_type.skidsteer'))->first();
            // $skidSterDriver = Driver::with('user')->whereDriverType(2)->first();
            // dump($job->toArray());
	        // dump($truck);
	        // dump($truckDriver);
            // dump($skidster);
            // dd($skidSterDriver);

            // if($truck && $truckDriver){

            //     //assign the job
            //     dd('in');
            // }

            //assign to job
            // $jobs->truck_id = $truck != null ? $truck->id:null;
            // $jobs->skidsteer_id = $skidster != null ? $skidster->id:null;
            // $jobs->truck_driver_id = $truckDriver != null ? $truckDriver->user_id:null;
            // $jobs->skidsteer_driver_id = $skidSterDriver != null ? $skidSterDriver->user_id:null;

            //$jobs->save();
        }

        dd('$getAllJobs->toArray()');

        $data = [
            "test" => hello
        ];

        return $data; 
    }

    public function finishJobFromFarm($id) 
    {

        // //complete the job and route.
        // $job = Job::where('id',$id)
        //         ->with(
        //             "customer",
        //             "manager",
        //             "farm",
        //             "service",
        //             "timeslots",
        //             "truck",
        //             "truckDriver"
        //         )->first();
        //             dd($job->farm);
        // if($job){
        //     Job::where('id',$id)->update(['status'=>config('constant.job_status.close')]);
        //     CustomerFarm::where('id',$job->farm->id)->update(['farm_active',3]);
        //     dd($job);
        // }

        $todayDate = Carbon::now()->format('Y-m-d');
        $getAllJobs = Job::with([
            "customer"=>function($query){
                $query->with("farmlist");
            },
            "manager",
            "farm",
            "service",
            "timeslots",
            ])
            // ->whereJobStatus(config('constant.job_status.open'))
            ->where('job_providing_date',$todayDate)
            ->orderBy('start_time', 'ASC')
            ->get();
            dump($getAllJobs->toArray());

         //get all distance from farm
         $farms = CustomerFarm::where('farm_active',1)->pluck('distance','id');
         $sortedFarms = $farms->sort();
         $sortedFarms = $sortedFarms->all();

         $distance = collect();
         $a = $getAllJobs->groupBy("start_time");
        //  dump($a);
         foreach($a as $data) { 
            //  dump($data);
            foreach($data as $item){
                $distance[$item->id] = $item->farm->distance; 
                $sorteddistance = $distance->sort();
                $sorteddistance = $sorteddistance->all();
            }

            $firstKey = key($sorteddistance);
            $firstVal = reset($sorteddistance);
            unset($sorteddistance[$firstKey]);
            dd($sorteddistance);

            foreach($data as $item){
                //assign job
                
                if(Job::where('id',$firstKey)->update(['truck_id'=>$job->truck_id,'truck_driver_id'=>$job->truck_driver_id])){
                    //job status updated.
                    Job::where('id',$firstKey)->update(['job_status'=>config('constant.job_status.assigned')]);
                    //update truck and driver status to unavailable.
                    Vehicle::where('id',$job->truck_id)->update(['status'=>config('constant.vehicle_status.unavailable')]);
                    Driver::where('id',$job->truck_driver_id)->update(['status'=>config('constant.driver_status.unavailable')]);
                    $newJob = Job::where('id',$firstKey)->with('farm')->first();
                    CustomerFarm::where('id',$newJob->farm->id)->update(['farm_active',2]);

                    break;
                }
            }
        }
        dd($sorteddistance);
            dd('out');




        //***********************************************************************
        //set to complete and check next job
        // Route::get('finish-job-from-farm/{id}', 'SetTaskController@finishJobFromFarm');
        // dd($id);
        $job = Job::where('id',$id)
                ->with(
                    "customer",
                    "manager",
                    "farm",
                    "service",
                    "timeslots",
                    "truck",
                    "skidsteer",
                    "truckDriver",
                    "skidsteer_driver"
                )->first();

        if($job){
            Job::where('id',$id)->update(['status'=>config('constant.job_status.completed')]);
        }
        //assign new job if exists


        //send to warehouse if no job exists

    }

    public function assignTecs() {
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
        // ->whereCreatedAt(Carbon::now())
        ->whereTruckId(null)
        ->whereSkidsteerId(null)
        ->whereTruckDriverId(null)
        ->whereSkidsteerDriverId(null)
        ->get(); 

        // $getTruckDriver = User::with(['driver' => function($query) {
        //     $query->whereDriverType(1);
        // }])
        // ->orDoesntHave('jobTruckDriver')
        // ->orWherehas('jobTruckDriver', function ($query) {
        //    $query->whereJobStatus(config('constant.job_status.open'));
        // })
        // ->whereRoleId(config('constant.roles.Driver'))->first();

        foreach($getAllJobs as $jobs) {
            $truck = Vehicle::whereVehicleType(config('constant.vehicle_type.truck'))->first();
            $truckDriver = Driver::with('user')->whereDriverType(1)->first();
            $skidster = Vehicle::whereVehicleType(config('constant.vehicle_type.skidsteer'))->first();
            $skidSterDriver = Driver::with('user')->whereDriverType(2)->first();
            dump($jobs);
	    dump($truck);
	    dump($truckDriver);
            dump($skidster);
            dd($skidSterDriver);

            //assign to job
            $jobs->truck_id = $truck != null ? $truck->id:null;
            $jobs->skidsteer_id = $skidster != null ? $skidster->id:null;
            $jobs->truck_driver_id = $truckDriver != null ? $truckDriver->user_id:null;
            $jobs->skidsteer_driver_id = $skidSterDriver != null ? $skidSterDriver->user_id:null;

            //$jobs->save();
        }
    }
}
