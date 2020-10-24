<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyRouteDetail extends Model
{
    use SoftDeletes;
 
    protected $table = 'daily_route_details';
    protected $fillable = [
        'date', 'driver_id', 'truck_id', 'morning_jobs', 'time_for_morning_jobs', 'spare_time_in_morning', 'over_time_in_morning', 'afternoon_jobs', 'time_for_afternoon_jobs',
        'spare_time_in_afternoon', 'over_time_in_afternoon', 'evening_jobs', 'time_for_evening_jobs', 'spare_time_in_evening', 'over_time_in_evening', 'total_time_for_route', 'total_spare_time_in_route',
        'route_start_time_by_driver', 'route_end_time_by_driver'
    ];
}