<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use SoftDeletes;
 
    protected $fillable = [
        'driver_id', 'monthly_income', 'month_number', 'year_yyyy', 'row_action_count'
    ];
    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }
}
