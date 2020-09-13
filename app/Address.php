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

    public $timestamps = false;
}
