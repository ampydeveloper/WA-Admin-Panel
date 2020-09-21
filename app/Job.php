<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;

    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $casts = [
        'options' => 'json',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_created_by','customer_id', 'manager_id', 'farm_id', 'gate_no', 'service_id', 'time_slots_id', 'job_providing_date', 'weight', 'is_repeating_job', 'repeating_days', 'images',
        'notes', 'amount', 'payment_mode', 'job_status', 'payment_status', 'quick_book', 'truck_id', 'truck_driver_id', 'skidsteer_id', 'skidsteer_driver_id', 'start_time', 'end_time'
    ];
    
    public function getCreatedAtAttribute($date) {
        return Carbon::parse($date)->format('d-M-y');
    }

    public function customer()
    {
        return $this->belongsTo('App\User', 'customer_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo('App\User', 'manager_id', 'id');
    }

    public function farm()
    {
        return $this->belongsTo('App\CustomerFarm', 'farm_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id', 'id');
    }

    public function truck()
    {
        return $this->belongsTo('App\Vehicle', 'truck_id', 'id');
    }

    public function skidsteer()
    {
        return $this->belongsTo('App\Vehicle', 'skidsteer_id', 'id');
    }

    public function truck_driver()
    {
        return $this->belongsTo('App\User', 'truck_driver_id', 'id');
    }

    public function skidsteer_driver()
    {
        return $this->belongsTo('App\User', 'skidsteer_driver_id', 'id');
    }

    public function timeslots()
    {
        return $this->belongsTo('App\TimeSlots', 'time_slots_id', 'id');
    }

    public function jobpayment()
    {
        return $this->hasOne('App\Payment', 'job_id');
    }

    public function employeeSalaries()
    {
        return $this->hasOne('App\EmployeeSalaries', 'user_id');
    }
}
