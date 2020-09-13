<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $fillable = [
        'title', 
        'description',
        'postedBy',
        'address',
        'rent',
        'area',
        'phoneContact',
        'rating',
        'views',
        'status',
        'active'
    ];

    protected $hidden = [
        'views'
    ];

    public $timestamps = false;

    
}
