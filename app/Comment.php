<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'text',
        'idApartment',
        'idUser',
        'commentedAt',
        'photo'
    ];

    public $timestamps = false;

    public function user() {
        return $this->hasOne('App\User', 'id', 'idUser');
    }
}
