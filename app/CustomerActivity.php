<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerActivity extends Model
{
 
    protected $table = 'customer_activities';
    protected $fillable = [
        'customer_id', 'job_id', 'created_by','activities'
    ];
}