<?php

namespace App\Utils;

use App\Address;
use Illuminate\Support\Facades\Http;

class ApartmentModificationHandler {

    public static function saveApartment($apartment, $body, $filter) {
        // temp object for store location option
        $address = null;

        // loop through body to handle updating
        foreach ($body as $key => $value) {
            // handle image file
            if (in_array($key, ['photo_1', 'photo_2', 'photo_3', 'photo_4'])) {
                // delete apartment's old photo
                if ($apartment->photo != null && $apartment->photo != '/photo/apartment/default.png') {
                    ImageHandler::deleteImage($apartment->photo);
                }
                // Store new photo
                $path = ImageHandler::storeImage($apartment->id, $value, 'apartment');

                // save image path in apartment->photo 
                $apartment->photo = $path;

            // handle address
            } else if ($key == 'address') {
                // parse json stringified
                $parsedAddress = json_decode($value);

                // apartment have already had some address?
                $address = $apartment->address != null ? $address = Address::find($apartment->address) : new Address;
                // update address info
                $addressFiler = ['street', 'ward', 'district', 'city'];
                $query =    $parsedAddress[$addressFiler[0]] . 
                            $parsedAddress[$addressFiler[1]] . 
                            $parsedAddress[$addressFiler[2]] . 
                            $parsedAddress[$addressFiler[3]];
                foreach ($parsedAddress as $key => $value) {
                    $address[$key] = $value;
                }
                
                $url = 'http://dev.virtualearth.net/REST/v1/Locations/'. $query .'?key=' . env('BINGMAP_API_KEY');
                $response = Http::get($url);
                dd($response);
                // save
                $address->save();
                // apartment have not any address yet?
                if ($apartment->address == null) $apartment->address = $address->id;
            
            // handle others
            } else if (in_array($key, $filter)) $apartment[$key] = $value;
        }

        //Create or save apartment using that infos
        $savedApartment = $apartment->save();
        unset($address->id);

        // attach address to apartment
        $savedApartment->address = $address;
        return $savedApartment;
    }
}