<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_name', 'price', 'description', 'service_type', 'service_image', 'slot_type', 'slot_time', 'service_for', 'service_created_by', 'time_taken_to_complete_service','overhead_cost'
    ];
    
    public function getSlotTypeAttribute($data) {
        return json_decode($data);
    }
}
