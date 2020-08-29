<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $fillable = [
        'title', 
        'description',
        'postedBy',
        'location',
        'rent',
        'area',
        'phoneContact',
        'rating',
        'views',
        'status' 
    ];

    public $timestamps = false;

    
}
