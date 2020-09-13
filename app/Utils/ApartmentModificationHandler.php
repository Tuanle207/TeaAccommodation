<?php

namespace App\Utils;

use App\Address;
use App\Apartment;
use App\ApartmentPhoto;
use App\Facility;
use Illuminate\Http\Exceptions\HttpResponseException;

use function GuzzleHttp\json_decode;

class ApartmentModificationHandler {

    public static function saveApartment($apartment, $body, $filter) {
        // temp object for store location option
        $address = null;
        $photos = [];
        $facilities = [];

        // loop through body to handle updating
        foreach ($body as $key => $value) {
            // 1.) handle address
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
            
            } // 2.) Handle image files
            else if (in_array($key, ['photo_1', 'photo_2', 'photo_3', 'photo_4'])) {
                // Delete apartment's old photos
                if ($apartment->id !== null) {
                    // get all photos
                    $tempPhotos = $apartment->photos;
                    // delete all photos
                    foreach ($tempPhotos as $index => $photo)
                        ImageHandler::deleteImage($tempPhotos); 
                }
                
                // Store new photo
                $path = ImageHandler::storeImage($value, 'apartment');

                // push to photos array
                array_push($photos, $path);

            } // 3.) Handle ficilities
            else if ($key === 'facilities') {
                $facilities = json_decode($value);
                
            } // 4.) Handle others 
            else if (in_array($key, $filter)) $apartment[$key] = $value;
        }

        // Save photos' info
        $apartment->photos = json_encode($photos);
        // Save facilities list
        $apartment->facilities = json_encode($facilities);

        // Create or save apartment using that infos
        $apartment->save();

        // attach address, photos, facilities to apartment
        $apartment->address = $address;
        $apartment->photos = $photos; 
        $apartment->facilities = $facilities;

        return $apartment;
    }

    public static function deleteApartment($apartment) {
        
        // get photos' paths
        $photos = $apartment->photos;
        
        // delete photos
        foreach ($photos as $index=>$photo)
            ImageHandler::deleteImage($photo);

        // finally delete apartment
        Apartment::where('id', $apartment->id)->delete();
    }
}