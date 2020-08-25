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

    public function apartmentPhoto()
    {
        return $this->hasMany('App\ApartmentPhoto', 'idApartment','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'postedBy', 'id');
    }

    public function userRating()
    {
        return $this->belongsToMany('App\User', 'ratings' ,'idApartment','idUser');
    }

    public function apartmentService()
    {
        return $this->belongsToMany('App\Service', 'apartment_services' ,'idApartment','idService');
    }

    public function location()
    {
        return $this->belongsTo('App\Location', 'location', 'id');
    }
    
    public function commentUser()
    {
        return $this->belongsToMany('App\Comment');
    }

    public function commentPhoto()
    {
        return $this->hasManyThrough('App\CommentPhoto', 'App\Comment');
    }
}
