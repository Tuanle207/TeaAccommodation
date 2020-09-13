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

    /**
     * The default values of propeties
     */
    protected $attributes = [
        'photo' => '/photo/user/default.png',
        'role' => 'user'
    ];

    public function address() {
        return $this->hasOne('App\Address', 'id', 'adrress');
    }

    public function apartments()
    {
        return $this->hasMany('App\Apartment', 'id');
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

    public static function scopePostedBy($query) {
        return $query->addSelect('id', 'name', 'photo');
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
