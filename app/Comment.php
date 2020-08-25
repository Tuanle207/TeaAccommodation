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
    ];

    public function commentPhoto()
    {
        return $this->hasMany('App\CommentPhoto', 'idApartment','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'idUser', 'id');
    }

    public function apartment()
    {
        return $this->belongsTo('App\Apartment', 'idApartment', 'id');
    }


}
