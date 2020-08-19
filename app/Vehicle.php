<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by', 'vehicle_type', 'company_name', 'truck_number', 'chaase_number', 'killometer', 'capacity',  'status',
        'document', 'insurance_document'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function vehicle_service()
    {
        return $this->hasOne('App\VehicleService');
    }

    public function vehicle_insurance()
    {
        return $this->hasOne('App\VehicleInsurance');
    }
}
