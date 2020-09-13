<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use function GuzzleHttp\json_decode;

class Apartment extends Model
{
    use Notifiable;
    
    protected $fillable = [
        'title', 
        'description',
        'postedBy',
        'address',
        'rent',
        'area',
        'phoneContact',
        'rating',
        'views',
        'status',
        'active'
    ];

    protected $hidden = [
        'views'
    ];

    public $timestamps = false;

    // Events
    public static function boot() {
        parent::boot();

        self::retrieved(function($apartment) {
            $apartment->photos = json_decode($apartment->photos);
            $apartment->facilities = json_decode($apartment->facilities);
        });
    }
    
}
