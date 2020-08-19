<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_name', 'service_for', 'service_rate', 'price', 'description', 'service_image', 'slot_type', 'slot_time'
    ];
}
