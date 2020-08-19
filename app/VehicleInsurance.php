<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleInsurance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vehicle_id', 'insurance_date', 'insurance_expiry', 'insurance_number'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }
}
