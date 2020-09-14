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
        'views',
        'visible'
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

    public function user() {
        return $this->hasOne('App\User', 'id', 'postedBy');
    }

    // Events
    public static function boot() {
        parent::boot();
       
        // decoded json data
        self::retrieved(function($apartment) {

            if ($apartment->photos !== null)
                $apartment->photos = json_decode($apartment->photos);

            if ($apartment->facilities !== null)
                $apartment->facilities = json_decode($apartment->facilities, true, 512, JSON_OBJECT_AS_ARRAY);
           
        });
    }
    
}
