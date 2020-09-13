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
        'photos',
        'facilities',
        'phoneContact',
        'rating',
        'views',
        'status',
        'active'
    ];

    protected $hidden = [
        'views'
    ];

    /**
     * The default values of propeties
     */
    protected $attributes = [
        'rating' => null,
        'status' => 'còn phòng',
        'active' => 1
    ];


    public $timestamps = false;

    // Events
    public static function boot() {
        parent::boot();

        // decoded json data
        self::retrieved(function($apartment) {
            $apartment->photos = json_decode($apartment->photos);
            $apartment->facilities = json_decode($apartment->facilities);
        });
    }
    
}
