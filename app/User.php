<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 
        'password',
        'passwordConfirm',
        'passwordChangeAt',
        'name',
        'address',
        'phoneNumber',
        'photo',
        'role' 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
    public function comment()
    {
        return $this->hasMany('App\Comment', 'idUser', 'id');
    }

    public function apartment()
    {
        return $this->hasMany('App\Apartment', 'idUser', 'id');
    }

    public function rating()
    {
        return $this->hasMany('App\Rating', 'idUser', 'id');
    }

    public function location()
    {
        return $this->belongsTo('App\Location', 'address', 'id');
    }

    public function apartmentRating()
    {
        return $this->belongsToMany('App\Rating');
    }

    public function commentApartment()
    {
        return $this->belongsToMany('App\Comment');
    }

    public function commentPhoto()
    {
        return $this->hasManyThrough('App\CommentPhoto', 'App\Comment');
    }

    public function apartmentPhoto()
    {
        return $this->hasManyThrough('App\ApartmentPhoto', 'App\Apartment');
    }
}
