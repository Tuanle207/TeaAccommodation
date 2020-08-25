<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'price',
    ];

    public function serviceApartment()
    {
        return $this->belongsToMany('App\Apartment', 'apartment_services' ,'idApartment','idUser');
    }
}
