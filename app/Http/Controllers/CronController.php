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
     
       /* $getAllJobs = Job::with(
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
        */
        
        /*
         $getTruckDriver = User::with(['driver' => function($query) {
             $query->whereDriverType(1);
         }])
         ->orDoesntHave('jobTruckDriver')
         ->orWherehas('jobTruckDriver', function ($query) {
            $query->whereJobStatus(config('constant.job_status.open'));
         })
         ->whereRoleId(config('constant.roles.Driver'))->first();
        */
        
        /*
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
         */
        
        
        //select driver 
        $cDrivers = Driver::where([
                ['driver_type', '=', config('constant.driver_type.truck_driver')],
                ['status', '=', config('constant.driver_status.available')]
            ])->get();
        
        $iArrDriversId = [];
        foreach($cDrivers as $driver)
        {
            array_push($iArrDriversId, $driver->id);
        }
       
        $cAllTrucks = Vehicle::where([
                ['vehicle_type', '=', config('constant.vehicle_type.truck')],
                ['status', '=', config('constant.vehicle_status.available')],
            ])->orderBy('assigned_job_row_action_count', 'desc')->get();
        
        $iArrTrucksId = [];
        foreach($cAllTrucks as $truck)
        {
            array_push($iArrTrucksId, $truck->id);
        }
        
        //find min cout of $iArrDriversId b/w $iArrTrucksId
        $iMinCount = min(count($iArrDriversId), count($iArrTrucksId));
        $iArrDriverTruckIdMapping = [];
        //assigning one driver to one truck for a day DriverId => TruckId (2 => )
        for ($index = 0; $index < $iMinCount; $index++)
        {
            $iArrDriverTruckIdMapping[$iArrDriversId[$index]] = $iArrTrucksId[$index];
        }
        
        //select all jobs in a particular interval time and loop for all interval 
        for($timeSlot = 1; $timeSlot <= config('constant.time_slots.total_time_slots'); $timeSlot++)
        {
            //All Open Jobs in particular interval
            $cIntervalJobs = Job::where([
                ['time_slots_id', '=', $timeSlot],
                ['job_status', '=', config('constant.job_status.open')]
            ])->with(['farm' => function($farmQuery){
                $farmQuery->select('id', 'latitude', 'longitude', 'distance')
                    ->orderBy('distance', 'asc')
                    ->get();
            }])->with(['service'=>function($serviceQuery){
                $serviceQuery->where('service_for', '=', config('constant.service_for.customer'))
                    ->select('id', 'service_name','service_type', 'service_for')
                    ->get();
            }])->get();
            
            //All truck
            $cTrucks = Vehicle::where([
                ['vehicle_type', '=', config('constant.vehicle_type.truck')],
                ['status', '=', config('constant.vehicle_status.available')],
            ])->orderBy('assigned_job_row_action_count', 'asc')->get();
            
            $arrAssignedJobId = []; //all assigned job in particular interval
            
            foreach ($cTrucks as $truck)
            {
                $bIsPreviousJobAssigned = FALSE; //Flag to track previous job assigned to Truck
                $iPreviousJobAssignedWeight = 0; //previous assigned job weight
                $iPreviousJobAssignedDistance = 0; //previous assigned job location to track the location of Truck
                foreach ($cIntervalJobs as $job)
                {
                    //job = round one truck dedicated for that job
                    if(($job->service['service_type'] == config('constant.service_type.by_trip')) 
                            &&  ($bIsPreviousJobAssigned == FALSE)
                            && (in_array($job->id, $arrAssignedJobId) == FALSE)
                            && (in_array($truck->id, $iArrDriverTruckIdMapping) == TRUE))
                    {
                        //Assigned job to truck here 
                        //code here to save job with truck id
                        $job->truck_id = $truck->id;
                        $job->truck_driver_id = array_search($truck->id, $iArrDriverTruckIdMapping);
                        $job->job_status = config('constant.job_status.assigned');
                        $job->save();
                        
                        $truck->assigned_job_row_action_count += 1;
                        $truck->save();
                         
                        //Assigned job push to track array
                        array_push($arrAssignedJobId, $job->id);
                        break; //cz now current truck is not eligible for taking next task
                    }
                    else 
                    {
                        if($truck->capacity >= $job->weight && (in_array($truck->id, $iArrDriverTruckIdMapping) == TRUE)
                            && (in_array($job->id, $arrAssignedJobId) == FALSE))
                        {
                            
                            if($iPreviousJobAssignedWeight > 0 && $bIsPreviousJobAssigned == TRUE) // next job for current truck
                            {
                                if(($truck->capacity - $iPreviousJobAssignedWeight) >= $job->weight 
                                    && (abs($iPreviousJobAssignedDistance - $job->farm['distance']) <= config('constant.range_cover.distance')))
                                {
                                    //Assigned job to truck here 
                                    //code here to save job with truck id
                                    $job->truck_id = $truck->id;
                                    $job->truck_driver_id = array_search($truck->id, $iArrDriverTruckIdMapping);
                                    $job->job_status = config('constant.job_status.assigned');
                                    $job->save();
                                    
                                    $truck->assigned_job_row_action_count += 1;
                                    $truck->save();

                                    //set all local variable 
                                    $iPreviousJobAssignedWeight += $job->weight;
                                    $iPreviousJobAssignedDistance += $job->farm['distance'];
                                    $bIsPreviousJobAssigned = TRUE;
                                    array_push($arrAssignedJobId, $job->id);
                                }
                            }
                            else //first job of current truck
                            {
                                //Assigned job to truck here 
                                //code here to save job with truck id
                                $job->truck_id = $truck->id;
                                $job->truck_driver_id = array_search($truck->id, $iArrDriverTruckIdMapping);
                                $job->job_status = config('constant.job_status.assigned');
                                $job->save();

                                $truck->assigned_job_row_action_count += 1;
                                $truck->save();

                                //set all local variable 
                                $iPreviousJobAssignedWeight += $job->weight;
                                $iPreviousJobAssignedDistance += $job->farm['distance'];
                                $bIsPreviousJobAssigned = TRUE;
                                array_push($arrAssignedJobId, $job->id);
                            }
                        }
                    }
                }
            }
        }
    }
}
