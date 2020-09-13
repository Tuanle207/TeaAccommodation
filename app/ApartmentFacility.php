<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApartmentFacility extends Model
{
    protected $primaryKey = [
        'idApartment',
        'idService',
    ];
    protected $fillable = [
        'idApartment',
        'idService',
    ];
}
