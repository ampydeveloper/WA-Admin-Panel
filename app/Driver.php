<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\TrackDriverTime;

class Driver extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'driver_type', 'driver_licence', 'expiry_date', 'document', 'salary_type', 'driver_salary', 'route_given'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function driverTime()
    {
        return $this->hasMany('App\TrackDriverTime', 'driver_id');
    }
    
    public function salary()
    {
        return $this->hasOne('App\Salary');
    }
    
    public function filterDriverTime(Driver $driver)
    {
        $DateTime = Carbon::now();
        return TrackDriverTime::where([
            ['month_number', '=', $DateTime->month],
            ['driver_id', '=', $driver->id]
            ])->get();
    }
}
