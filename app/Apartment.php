<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
class Apartment extends Model
{
    use Notifiable;
    use SoftDeletes;
    
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
        'visible' => 1
    ];


    public $timestamps = false;

    public function user() {
        return $this->hasOne('App\User', 'id', 'postedBy');
    }

    public function address() {
        return $this->hasOne('App\Address', 'id', 'address');
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

        self::saving(function($apartment) {
            $apartment->lastUpdatedAt = Carbon::now();
        });

    //     self::deleting(function($apartment) { // before delete() method call this
            
    //         // delete all related rating
    //         Rating::where('idApartment', '=', $apartment->id)->delete();
            
    //         // delete all related comment
    //         Comment::where('idApartment', '=', $apartment->id)->delete();
    //    });
    }
    
}
