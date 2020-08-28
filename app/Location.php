<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
        'description'
    ];

    public $timestamps = false;

    public function apartment()
    {
        return $this->hasMany('App\Apartment', 'location', 'id');
    }

    public function user()
    {
        return $this->hasMAny('App\User','address','id');
    }
}
