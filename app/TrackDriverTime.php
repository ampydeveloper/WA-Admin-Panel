<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackDriverTime extends Model
{
    use SoftDeletes;
 
    protected $fillable = [
        'driver_id', 'start_time', 'stop_time', 'day_number', 'month_number', 'year_yyyy', 'row_action_count'
    ];
    
    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }
}


