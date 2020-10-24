<?php

namespace App\Http\Controllers;

use App\Job;
use App\User;
use App\Driver;
use App\Vehicle;
use App\DailyRouteDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    /**
     * assign techs
     */
    public function assignTecs() {
     
        $speed = 40;
        $maxRouteDuration = 180;
        $timeTakenByService = config('constant.time_taken_to_complete_service_reverse');
        $counter = 1;
        
        for($i = 0; $i<$counter;) {


            $cDrivers = Driver::where([
                            ['driver_type', '=', config('constant.driver_type.truck_driver')],
                            ['status', '=', config('constant.driver_status.available')],
                            ['route_given', '=', 0]
                    ])->pluck('user_id');
            
            $cAllTrucks = Vehicle::where([
                            ['vehicle_type', '=', config('constant.vehicle_type.truck')],
                            ['status', '=', config('constant.vehicle_status.available')],
                            ['route_assigned', '=', 0]
                    ])->pluck('id');

            //find min cout of $iArrDriversId b/w $iArrTrucksId
            $iMinCount = min(count($cDrivers), count($cAllTrucks));
            $iArrDriverTruckIdMapping = [];

            for ($index = 0; $index < $iMinCount; $index++) {
                $iArrDriverTruckIdMapping[$index]['driver_id'] = $cDrivers[$index];
                $iArrDriverTruckIdMapping[$index]['truck_id'] = $cAllTrucks[$index];
            }

            $all_jobs = Job::where([
                            ['job_providing_date', '=', date("Y-m-d")],
                            ['is_repeating_job', '=', config('constant.repeating_job.no')],
                            ['job_status', '=', config('constant.job_status.open')]
                    ])->select('id', 'farm_id', 'service_id', 'time_slots_id', 'weight', 'is_repeating_job')->with('farm', 'service')->get()->groupBy('farm.farm_zipcode');

            if ($all_jobs->count() && count($iArrDriverTruckIdMapping)) {

                // Making route according to zip code.
                foreach ($all_jobs as $key => $jobs) {
                    $durationRouteGotMorning = 0;
                    $durationRouteGotAfternoon = 0;
                    $durationRouteGotEvening = 0;
                    $maxRouteDurationMorning = 180;
                    $maxRouteDurationAfternoon = 180;
                    $maxRouteDurationEvening = 180;
                    $job_ids = [];

                    if (count($iArrDriverTruckIdMapping)) {
                        foreach ($jobs as $job) {
                            $communiteTime = ($job->farm->distance / $speed) * 60;
                            $timeTakenByServiceToComplete = $timeTakenByService[$job->service->time_taken_to_complete_service];

                            if ($job->time_slots_id == config('constant.service_slot_type.morning')) {
                                if ($durationRouteGotMorning == 0) {
                                    $durationRouteGotMorning = $communiteTime + $timeTakenByServiceToComplete;
                                    $job_ids['morning'][$job->id] = [
                                        'job_time_slot' => config('constant.service_slot_type_inverse.1'),
                                        'latitude' => $job->farm->latitude,
                                        'longitude' => $job->farm->longitude,
                                    ];
                                } else {
                                    $checkIfTruckHasTime = $maxRouteDurationMorning - $durationRouteGotMorning;
                                    if ($checkIfTruckHasTime >= ($communiteTime + $timeTakenByServiceToComplete)) {
                                        $durationRouteGotMorning += $communiteTime + $timeTakenByServiceToComplete;
                                        $job_ids['morning'][$job->id] = [
                                            'job_time_slot' => config('constant.service_slot_type_inverse.1'),
                                            'latitude' => $job->farm->latitude,
                                            'longitude' => $job->farm->longitude,
                                        ];
                                    } else {
                                        $unRoutedJobs['morning'][$key][$job->id] = [
                                            'job_time_slot' => config('constant.service_slot_type_inverse.1'),
                                            'latitude' => $job->farm->latitude,
                                            'longitude' => $job->farm->longitude,
                                            'time_needed_to_complete_job' => $communiteTime + $timeTakenByServiceToComplete,
                                        ];
                                    }
                                }
                            } elseif ($job->time_slots_id == config('constant.service_slot_type.afternoon')) {
                                if ($durationRouteGotAfternoon == 0) {
                                    $durationRouteGotAfternoon = $communiteTime + $timeTakenByServiceToComplete;
                                    $job_ids['afternoon'][$job->id] = [
                                        'job_time_slot' => config('constant.service_slot_type_inverse.2'),
                                        'latitude' => $job->farm->latitude,
                                        'longitude' => $job->farm->longitude,
                                    ];
                                } else {
                                    $checkIfTruckHasTime = $maxRouteDurationMorning - $durationRouteGotAfternoon;
                                    if ($checkIfTruckHasTime >= ($communiteTime + $timeTakenByServiceToComplete)) {
                                        $durationRouteGotAfternoon += $communiteTime + $timeTakenByServiceToComplete;
                                        $job_ids['afternoon'][$job->id] = [
                                            'job_time_slot' => config('constant.service_slot_type_inverse.2'),
                                            'latitude' => $job->farm->latitude,
                                            'longitude' => $job->farm->longitude,
                                        ];
                                    } else {
                                        $unRoutedJobs['afternoon'][$key][$job->id] = [
                                            'job_time_slot' => config('constant.service_slot_type_inverse.2'),
                                            'latitude' => $job->farm->latitude,
                                            'longitude' => $job->farm->longitude,
                                            'time_needed_to_complete_job' => $communiteTime + $timeTakenByServiceToComplete,
                                        ];
                                    }
                                }
                            } else {
                                if ($durationRouteGotEvening == 0) {
                                    $durationRouteGotEvening = $communiteTime + $timeTakenByServiceToComplete;
                                    $job_ids['evening'][$job->id] = [
                                        'job_time_slot' => config('constant.service_slot_type_inverse.3'),
                                        'latitude' => $job->farm->latitude,
                                        'longitude' => $job->farm->longitude,
                                    ];
                                } else {
                                    $checkIfTruckHasTime = $maxRouteDurationMorning - $durationRouteGotEvening;
                                    if ($checkIfTruckHasTime >= ($communiteTime + $timeTakenByServiceToComplete)) {
                                        $durationRouteGotEvening += $communiteTime + $timeTakenByServiceToComplete;
                                        $job_ids['evening'][$job->id] = [
                                            'job_time_slot' => config('constant.service_slot_type_inverse.3'),
                                            'latitude' => $job->farm->latitude,
                                            'longitude' => $job->farm->longitude,
                                        ];
                                    } else {
                                        $unRoutedJobs['evening'][$key][$job->id] = [
                                            'job_time_slot' => config('constant.service_slot_type_inverse.3'),
                                            'latitude' => $job->farm->latitude,
                                            'longitude' => $job->farm->longitude,
                                            'time_needed_to_complete_job' => $communiteTime + $timeTakenByServiceToComplete,
                                        ];
                                    }
                                }
                            }

                            if (isset($job_ids['morning'])) {
                                $route_details[$key]['morning'] = [
                                    'time_taken_to_complete' => $durationRouteGotMorning,
                                    'spare_time' => $maxRouteDurationMorning - $durationRouteGotMorning,
                                    'job_ids' => $job_ids['morning'],
                                ];
                            }
                            if (isset($job_ids['afternoon'])) {
                                $route_details[$key]['afternoon'] = [
                                    'time_taken_to_complete' => $durationRouteGotAfternoon,
                                    'spare_time' => $maxRouteDurationAfternoon - $durationRouteGotAfternoon,
                                    'job_ids' => $job_ids['afternoon'],
                                ];
                            }
                            if (isset($job_ids['evening'])) {
                                $route_details[$key]['evening'] = [
                                    'time_taken_to_complete' => $durationRouteGotEvening,
                                    'spare_time' => $maxRouteDurationEvening - $durationRouteGotEvening,
                                    'job_ids' => $job_ids['evening'],
                                ];
                            }
                        }
                        $route_details[$key]['total_time_taken_to_complete_route'] = $durationRouteGotMorning + $durationRouteGotAfternoon + $durationRouteGotEvening;
                        $route_details[$key]['total_spare_time'] = 540 - $route_details[$key]['total_time_taken_to_complete_route'];
                        $route_details[$key]['driver_id'] = $iArrDriverTruckIdMapping[0]['driver_id'];
                        $route_details[$key]['truck_id'] = $iArrDriverTruckIdMapping[0]['truck_id'];
                        unset($iArrDriverTruckIdMapping[0]);
                        $iArrDriverTruckIdMapping = array_values($iArrDriverTruckIdMapping);
                    }
                }

                // If all jobs are not fitted in route, then assigning jobs to other routes in same time zone.
                if (isset($unRoutedJobs)) {
                    foreach ($route_details as $key => $detail) {
                        $job_ids = [];
                        if (!isset($detail['morning'])) {
                            if (isset($unRoutedJobs['morning'])) {
                                if ($detail['total_spare_time'] >= 180) {
                                    $maxRouteDurationMorning = 180;
                                } else {
                                    $maxRouteDurationMorning = $detail['total_spare_time'];
                                }
                                $durationRouteGotMorning = 0;
                                foreach ($unRoutedJobs['morning'] as $routeKey => $unRoutedJob) {
                                    foreach ($unRoutedJob as $jobIdKey => $job) {
                                        if ($job['time_needed_to_complete_job'] <= $maxRouteDurationMorning) {
                                            $job_ids['morning'][$jobIdKey] = [
                                                'job_time_slot' => config('constant.service_slot_type_inverse.1'),
                                                'latitude' => $job['latitude'],
                                                'longitude' => $job['longitude'],
                                            ];
                                            $durationRouteGotMorning += $job['time_needed_to_complete_job'];
                                            $maxRouteDurationMorning = $maxRouteDurationMorning - $job['time_needed_to_complete_job'];

                                            if (count($unRoutedJobs['morning']) == 1) {
                                                unset($unRoutedJobs['morning']);
                                            } else {
                                                if (count($unRoutedJobs['morning'][$routeKey]) == 1) {
                                                    unset($unRoutedJobs['morning'][$routeKey]);
                                                } else {
                                                    unset($unRoutedJobs['morning'][$routeKey][$jobIdKey]);
                                                }
                                            }
                                        }
                                    }
                                }
                                $route_details[$key]['morning']['time_taken_to_complete'] = $durationRouteGotMorning;
                                $route_details[$key]['morning']['spare_time'] = $maxRouteDurationMorning;
                                $route_details[$key]['morning']['job_ids'] = $job_ids['morning'];
                                $route_details[$key]['total_time_taken_to_complete_route'] += $durationRouteGotMorning;
                                $route_details[$key]['total_spare_time'] = $route_details[$key]['total_spare_time'] - $durationRouteGotMorning;
                            }
                        }

                        if (!isset($detail['afternoon'])) {
                            if (isset($unRoutedJobs['afternoon'])) {
                                if ($detail['total_spare_time'] >= 180) {
                                    $maxRouteDurationMorning = 180;
                                } else {
                                    $maxRouteDurationMorning = $detail['total_spare_time'];
                                }
                                $durationRouteGotMorning = 0;
                                foreach ($unRoutedJobs['afternoon'] as $routeKey => $unRoutedJob) {
                                    foreach ($unRoutedJob as $jobIdKey => $job) {
                                        if ($job['time_needed_to_complete_job'] <= $maxRouteDurationMorning) {
                                            $job_ids['afternoon'][$jobIdKey] = [
                                                'job_time_slot' => config('constant.service_slot_type_inverse.2'),
                                                'latitude' => $job['latitude'],
                                                'longitude' => $job['longitude'],
                                            ];
                                            $durationRouteGotMorning += $job['time_needed_to_complete_job'];
                                            $maxRouteDurationMorning = $maxRouteDurationMorning - $job['time_needed_to_complete_job'];
                                            if (count($unRoutedJobs['afternoon']) == 1) {
                                                unset($unRoutedJobs['afternoon']);
                                            } else {
                                                if (count($unRoutedJobs['afternoon'][$routeKey]) == 1) {
                                                    unset($unRoutedJobs['afternoon'][$routeKey]);
                                                } else {
                                                    unset($unRoutedJobs['afternoon'][$routeKey][$jobIdKey]);
                                                }
                                            }
                                        }
                                    }
                                }
                                $route_details[$key]['afternoon']['time_taken_to_complete'] = $durationRouteGotMorning;
                                $route_details[$key]['afternoon']['spare_time'] = $maxRouteDurationMorning;
                                $route_details[$key]['afternoon']['job_ids'] = $job_ids['afternoon'];
                                $route_details[$key]['total_time_taken_to_complete_route'] += $durationRouteGotMorning;
                                $route_details[$key]['total_spare_time'] = $route_details[$key]['total_spare_time'] - $durationRouteGotMorning;
                            }
                        }

                        if (!isset($detail['evening'])) {
                            if (isset($unRoutedJobs['evening'])) {
                                if ($detail['total_spare_time'] >= 180) {
                                    $maxRouteDurationMorning = 180;
                                } else {
                                    $maxRouteDurationMorning = $detail['total_spare_time'];
                                }
                                $durationRouteGotMorning = 0;
                                foreach ($unRoutedJobs['evening'] as $routeKey => $unRoutedJob) {
                                    foreach ($unRoutedJob as $jobIdKey => $job) {
                                        if ($job['time_needed_to_complete_job'] <= $maxRouteDurationMorning) {
                                            $job_ids['evening'][$jobIdKey] = [
                                                'job_time_slot' => config('constant.service_slot_type_inverse.3'),
                                                'latitude' => $job['latitude'],
                                                'longitude' => $job['longitude'],
                                            ];
                                            $durationRouteGotMorning += $job['time_needed_to_complete_job'];
                                            $maxRouteDurationMorning = $maxRouteDurationMorning - $job['time_needed_to_complete_job'];
                                            if (count($unRoutedJobs['evening']) == 1) {
                                                unset($unRoutedJobs['evening']);
                                            } else {
                                                if (count($unRoutedJobs['evening'][$routeKey]) == 1) {
                                                    unset($unRoutedJobs['evening'][$routeKey]);
                                                } else {
                                                    unset($unRoutedJobs['evening'][$routeKey][$jobIdKey]);
                                                }
                                            }
                                        }
                                    }
                                }
                                $route_details[$key]['evening']['time_taken_to_complete'] = $durationRouteGotMorning;
                                $route_details[$key]['evening']['spare_time'] = $maxRouteDurationMorning;
                                $route_details[$key]['evening']['job_ids'] = $job_ids['evening'];
                                $route_details[$key]['total_time_taken_to_complete_route'] += $durationRouteGotMorning;
                                $route_details[$key]['total_spare_time'] = $route_details[$key]['total_spare_time'] - $durationRouteGotMorning;
                            }
                        }
                    }
                }

                // If still has pending job, assigning job to that route which has time with them.
                if (count($unRoutedJobs)) {
                    if (isset($unRoutedJobs['morning'])) {
                        foreach ($unRoutedJobs['morning'] as $k => $value) {
                            foreach ($value as $key => $job) {

                                foreach ($route_details as $routeKey => $detail) {
                                    if ($detail['total_spare_time'] >= $job['time_needed_to_complete_job']) {
                                        $route_details[$routeKey]['total_time_taken_to_complete_route'] += $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['total_spare_time'] = $route_details[$routeKey]['total_spare_time'] - $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['morning']['time_taken_to_complete'] += $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['morning']['spare_time'] = $route_details[$routeKey]['afternoon']['spare_time'] - $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['morning']['job_ids'][$key]['job_time_slot'] = $job['job_time_slot'];
                                        $route_details[$routeKey]['morning']['job_ids'][$key]['latitude'] = $job['latitude'];
                                        $route_details[$routeKey]['morning']['job_ids'][$key]['longitude'] = $job['longitude'];
                                        if (count($unRoutedJobs['morning']) == 1) {
                                            unset($unRoutedJobs['morning']);
                                        } else {
                                            if (count($unRoutedJobs['morning'][$routeKey]) == 1) {
                                                unset($unRoutedJobs['morning'][$routeKey]);
                                            } else {
                                                unset($unRoutedJobs['morning'][$routeKey][$jobIdKey]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (isset($unRoutedJobs['afternoon'])) {
                        foreach ($unRoutedJobs['afternoon'] as $k => $value) {
                            foreach ($value as $key => $job) {

                                foreach ($route_details as $routeKey => $detail) {
                                    if ($detail['total_spare_time'] >= $job['time_needed_to_complete_job']) {
                                        $route_details[$routeKey]['total_time_taken_to_complete_route'] += $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['total_spare_time'] = $route_details[$routeKey]['total_spare_time'] - $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['afternoon']['time_taken_to_complete'] += $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['afternoon']['spare_time'] = $route_details[$routeKey]['afternoon']['spare_time'] - $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['afternoon']['job_ids'][$key]['job_time_slot'] = $job['job_time_slot'];
                                        $route_details[$routeKey]['afternoon']['job_ids'][$key]['latitude'] = $job['latitude'];
                                        $route_details[$routeKey]['afternoon']['job_ids'][$key]['longitude'] = $job['longitude'];
                                        if (count($unRoutedJobs['afternoon']) == 1) {
                                            unset($unRoutedJobs['afternoon']);
                                        } else {
                                            if (count($unRoutedJobs['afternoon'][$routeKey]) == 1) {
                                                unset($unRoutedJobs['afternoon'][$routeKey]);
                                            } else {
                                                unset($unRoutedJobs['afternoon'][$routeKey][$jobIdKey]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (isset($unRoutedJobs['evening'])) {
                        foreach ($unRoutedJobs['evening'] as $k => $value) {
                            foreach ($value as $key => $job) {

                                foreach ($route_details as $routeKey => $detail) {
                                    if ($detail['total_spare_time'] >= $job['time_needed_to_complete_job']) {
                                        $route_details[$routeKey]['total_time_taken_to_complete_route'] += $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['total_spare_time'] = $route_details[$routeKey]['total_spare_time'] - $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['evening']['time_taken_to_complete'] += $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['evening']['spare_time'] = $route_details[$routeKey]['afternoon']['spare_time'] - $job['time_needed_to_complete_job'];
                                        $route_details[$routeKey]['evening']['job_ids'][$key]['job_time_slot'] = $job['job_time_slot'];
                                        $route_details[$routeKey]['evening']['job_ids'][$key]['latitude'] = $job['latitude'];
                                        $route_details[$routeKey]['evening']['job_ids'][$key]['longitude'] = $job['longitude'];
                                        if (count($unRoutedJobs['evening']) == 1) {
                                            unset($unRoutedJobs['evening']);
                                        } else {
                                            if (count($unRoutedJobs['evening'][$routeKey]) == 1) {
                                                unset($unRoutedJobs['evening'][$routeKey]);
                                            } else {
                                                unset($unRoutedJobs['evening'][$routeKey][$jobIdKey]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            foreach ($route_details as $detail) {
                $daily_route_details = new DailyRouteDetail();
                $daily_route_details->date = date("Y-m-d");
                $daily_route_details->driver_id = $detail['driver_id'];
                $daily_route_details->truck_id = $detail['truck_id'];
                $daily_route_details->morning_jobs = json_encode($detail['morning']['job_ids']);
                $daily_route_details->time_for_morning_jobs = $detail['morning']['time_taken_to_complete'];
                $daily_route_details->spare_time_in_morning = $detail['morning']['spare_time'];
                $daily_route_details->over_time_in_morning = ($detail['morning']['spare_time'] < 0) ? 1 : 0;
                $daily_route_details->afternoon_jobs = json_encode($detail['afternoon']['job_ids']);
                $daily_route_details->time_for_afternoon_jobs = $detail['afternoon']['time_taken_to_complete'];
                $daily_route_details->spare_time_in_afternoon = $detail['afternoon']['spare_time'];
                $daily_route_details->over_time_in_afternoon = ($detail['afternoon']['spare_time'] < 0) ? 1 : 0;
                $daily_route_details->evening_jobs = json_encode($detail['evening']['job_ids']);
                $daily_route_details->time_for_evening_jobs = $detail['evening']['time_taken_to_complete'];
                $daily_route_details->spare_time_in_evening = $detail['evening']['spare_time'];
                $daily_route_details->over_time_in_evening = ($detail['evening']['spare_time'] < 0) ? 1 : 0;
                $daily_route_details->total_time_for_route = $detail['total_time_taken_to_complete_route'];
                $daily_route_details->total_spare_time_in_route = $detail['total_spare_time'];

                if ($daily_route_details->save()) {
                    Driver::where('user_id', $detail['driver_id'])->update(['route_given' => 1]);
                    Vehicle::where('id', $detail['truck_id'])->update(['route_assigned' => 1]);
                    if (isset($detail['morning'])) {
                        foreach ($detail['morning']['job_ids'] as $jobId => $value) {
                            Job::where('id', $jobId)->update(['job_status' => 1, 'truck_id' => $detail['truck_id'], 'truck_driver_id' => $detail['driver_id']]);
                        }
                    }
                    if (isset($detail['afternoon'])) {
                        foreach ($detail['afternoon']['job_ids'] as $jobId => $value) {
                            Job::where('id', $jobId)->update(['job_status' => 1, 'truck_id' => $detail['truck_id'], 'truck_driver_id' => $detail['driver_id']]);
                        }
                    }
                    if (isset($detail['evening'])) {
                        foreach ($detail['evening']['job_ids'] as $jobId => $value) {
                            Job::where('id', $jobId)->update(['job_status' => 1, 'truck_id' => $detail['truck_id'], 'truck_driver_id' => $detail['driver_id']]);
                        }
                    }
                }
            }
            if (count($unRoutedJobs) == 0 || count($iArrDriverTruckIdMapping) == 0) {
                $i++;
            }
        }


        dd('end');


        return response()->json([
                    'status' => true,
                    'data' => $route_details_morning
                        ], 200);
        
        dd('end');
        
        
        
        
        
        
        
        
        
        
        
        
        
        dd($drivers->toArray());
        
        
        
        
        $afternoon_jobs = Job::where([
            ['job_providing_date','=', date("Y-m-d", strtotime("+1 day"))],
            ['time_slots_id', '=', config('constant.service_slot_type.afternoon')],
            ['is_repeating_job', '=',config('constant.repeating_job.no')]
        ])->with('farm', 'service')->get()->groupBy('farm.farm_zipcode');
        
//        dd($jobs->toArray());
        foreach ($afternoon_jobs as $key => $jobs) {
            $durationRouteGot = 0;
            $job_ids = [];
            reset($job_ids);
            $flag = 0;
            foreach ($jobs as $job) {
                $communiteTime = ($job->farm->distance / $speed) * 60;
                $timeTakenByServiceToComplete = $timeTakenByService[$job->service->time_taken_to_complete_service];
                if ($durationRouteGot == 0) {
                    $durationRouteGot = $communiteTime + $timeTakenByServiceToComplete;
                    $job_ids[$job->id] = 2;
                } else {
                    $checkIfTruckHasTime = $maxRouteDuration - $durationRouteGot;
                    if ($checkIfTruckHasTime >= ($communiteTime + $timeTakenByServiceToComplete)) {
                        $durationRouteGot += $communiteTime + $timeTakenByServiceToComplete;
                        $job_ids[$job->id] = 2;
                    } else {
                        $unRoutedJobs_afternoon[$key][$job->id] = [
                            'job_time_slot' => 2,
                            'time_needed_to_complete_job' => $communiteTime + $timeTakenByServiceToComplete,
                        ];
                    }
                }
            }
            $route_details_afternoon[$key] = [
                'time_taken_to_complete' => $durationRouteGot,
                'spare_time' => $maxRouteDuration - $durationRouteGot,
                'job_ids' => $job_ids,
            ];
        }
        
        dump($route_details_afternoon);
//        dd($unRoutedJobs_afternoon);
        
        if(isset($unRoutedJobs_morning)) {
            foreach($unRoutedJobs_morning as $key => $unRoutedJob) {
                dump($key);
                if (isset($route_details_afternoon[$key])) {
                    foreach ($unRoutedJob as $job_id => $job) {
                        if ($job['time_needed_to_complete_job'] <= $route_details_afternoon[$key]['spare_time']) {
                            $route_details_afternoon[$key]['time_taken_to_complete'] += $job['time_needed_to_complete_job'];
                            $route_details_afternoon[$key]['spare_time'] = $route_details_afternoon[$key]['spare_time'] - $job['time_needed_to_complete_job'];
                            $route_details_afternoon[$key]['job_ids'][$job_id] = 'late';
                            unset($unRoutedJobs_morning[$key][$job_id]);
                        }
                    }
                }
            }
        }
        
        dump($route_details_afternoon);
        dd($unRoutedJobs_morning);
 dd('end');


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
            $truckAssignedToDrivers[] = [
                'truck_id' => $iArrTrucksId[$index],
                'driver_id' => $iArrDriversId[$index]
            ] ;
        }
        dump($iArrDriversId);
        dump($iArrTrucksId);
        dd($truckAssignedToDrivers);
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
