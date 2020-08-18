<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleService extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vehicle_id', 'service_date', 'service_expiry', 'service_killometer', 'receipt', 'document', 'note'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }
}
