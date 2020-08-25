<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApartmentPhoto extends Model
{
    protected $fillable = [
        'source',
    ];

    public function apartment()
    {
        return $this->belongsTo('App\Apartment', 'idApartment', 'id');
    }
}
