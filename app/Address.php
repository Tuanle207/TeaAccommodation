<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
        'street',
        'ward',
        'district',
        'city'
    ];

    protected $hidden = [
        'type'
    ];

    public $timestamps = false;
}
