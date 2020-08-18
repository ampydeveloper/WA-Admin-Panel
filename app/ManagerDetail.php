<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagerDetail extends Model
{
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $casts = [
        'manager_id' => 'json',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'salary', 'identification_number', 'joining_date', 'releaving_date', 'document'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function farms()
    {
        return $this->hasOne('App\CustomerFarm', 'manager_id');
    }
}
