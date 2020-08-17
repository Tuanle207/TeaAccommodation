<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApartmentService extends Model
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
