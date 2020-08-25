<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleInsurance extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vehicle_id', 'insurance_date', 'insurance_expiry', 'insurance_number', 'document', 'notes'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }
}
