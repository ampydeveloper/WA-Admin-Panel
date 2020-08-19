<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Job;
use App\User;
use App\Driver;
use App\Vehicle;

class CronController extends Controller
{
    /**
     * assign techs
     */
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
            
            //assign to job 
            $jobs->truck_id = $truck != null ? $truck->id:null;
            $jobs->skidsteer_id = $skidster != null ? $skidster->id:null;
            $jobs->truck_driver_id = $truckDriver != null ? $truckDriver->user_id:null;
            $jobs->skidsteer_driver_id = $skidSterDriver != null ? $skidSterDriver->user_id:null;

            $jobs->save();
        }
    }
}
