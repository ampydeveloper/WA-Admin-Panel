<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFarm extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'farm_address', 'farm_city', 'farm_image', 'farm_province', 'farm_unit', 'farm_zipcode', 'farm_active', 'latitude', 'longitude','distance_warehouse', 'distance_dumping_area', 'created_by'
    ];

    public function manager()
    {
        return $this->belongsTo('App\User', 'farm_id');
    }

    public function farmManager()
    {
        return $this->hasMany('App\User', 'farm_id');
    }
    public function totalJobs() {
        return $this->hasMany('App\Job', 'farm_id');
    }
    public function latestJob() {
        return $this->hasOne('App\Job', 'farm_id')->latest();
    }
}
