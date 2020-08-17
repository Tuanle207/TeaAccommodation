<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $primaryKey = [
        'idApartment',
        'idUser',
    ];
    protected $fillable = [
        'idApartment',
        'idService',
        'rating'
    ];
}
