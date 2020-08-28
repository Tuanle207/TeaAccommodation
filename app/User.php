<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

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
        'name',
        'address',
        'phoneNumber',
        'photo',
        'role',
        'passwordChangedAt'
    ];
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'passwordConfirm',
        'passwordChangedAt'
    ];
    public function comment()
    {
        return $this->hasMany('App\Comment', 'idUser', 'id');
    }

    public function apartments()
    {
        return $this->hasMany('App\Apartment', 'id');
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

   
    // Events
    public static function boot() {
        parent::boot();

        self::saving(function($user) {
            self::handlePassword($user);
        });
    }

    // Query Scopes
    public static function scopeFields($query) {
        return $query->addSelect('id', 'name', 'photo', 'phoneNumber', 'address', 'email', 'role');
    }

    private static function handlePassword($user) {
        if ($user->wasChanged('password') || $user->isDirty('password') || !$user->exists) {
            $user->password = Hash::make($user->password, [
                'rounds' => 12
            ]);
            $user->passwordConfirm = null;
            $user->passwordChangedAt = Carbon::now();
        }
    }
}
