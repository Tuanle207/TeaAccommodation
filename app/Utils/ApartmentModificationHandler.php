<?php

namespace App\Utils;

use App\Address;
use App\ApartmentPhoto;
use App\Facility;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApartmentModificationHandler {

    public static function saveApartment($apartment, $body, $filter) {
        // temp object for store location option
        $address = null;
        $photos = [];

        // loop through body to handle updating
        foreach ($body as $key => $value) {
            if ($key === 'address') {
                // parse json stringified
                $parsedAddress = json_decode($value);

                // apartment have already had some address?
                $address = !property_exists($key, $apartment) ? new Address : Address::find($apartment->address); 
            
                // update address info
                foreach ($parsedAddress as $key => $value)
                    if (in_array($key, ['street', 'ward', 'district', 'city', 'latitude', 'longitude']))
                        $address[$key] = $value;
                
                // save address in DB
                $address->save();

                // apartment have not any address yet?
                if (!property_exists($key, $apartment)) $apartment->address = $address->id;
                unset($address->id);
            
            } // Handle image files
            else if (in_array($key, ['photo_1', 'photo_2', 'photo_3', 'photo_4'])) {
                // Delete apartment's old photo
                if ($apartment->id !== null) {
                    // get all photos
                    $tempPhotos = ApartmentPhoto::where('idApartment', $apartment->id);
                    // delete all photos
                    foreach ($tempPhotos as $index => $photo)
                        ImageHandler::deleteImage($tempPhotos); 
                }
                
                // Store new photo
                $path = ImageHandler::storeImage($apartment->id, $value, 'apartment');

                // push to photos array
                array_push($photos, $path);

            } else if ($key === 'facilities') {

                
            } // handle others 
            else if (in_array($key, $filter)) $apartment[$key] = $value;
        }

        // Save photos' info
        $apartment->photos = json_encode($photos);

        // Create or save apartment using that infos
        $apartment->save();

        // attach address, photos to apartment
        $apartment->address = $address;
        $apartment->photos = $photos; 

        return $apartment;
    }
}