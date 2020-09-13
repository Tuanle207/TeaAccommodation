<?php

namespace App\Utils;

use App\Address;
use App\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserModificationHanlder {
    /**
     * update or help with create and save new user
     */
    public static function saveUser($user, $body, $filter) {

        if ($user === null) {
            $user = new User;
        }
            
        $address = null;


        // loop through body to handle updating
        foreach ($body as $key => $value) {
            // 1. handle address
            if ($key === 'address') {
                // parse json stringified
                $parsedAddress = json_decode($value);

                // only accept Ho Chi Minh address
                if (strtolower(StringHandler::vn_to_str($parsedAddress->city)) !== 'ho chi minh') {
                    $errMessage = (object)[];
                    $errMessage->address = ['Chỉ chấp nhận địa chỉ ở TP Hồ Chí Minh'];
                    throw new HttpResponseException(response()->json([
                        'status' => 'fail',
                        'messages' => $errMessage
                    ], 400));
                }

                // user have already had some address? otherwise, create new Address model
                $address = !property_exists($key, $user) ? new Address : Address::find($user->address); 

                // update address info
                foreach ($parsedAddress as $key => $value)
                    if (in_array($key, ['street', 'ward', 'district', 'city', 'latitude', 'longitude']))
                        $address[$key] = $value;
                

                // save address in DB
                $address->save();
                
                // user have not any address yet?
                if (!property_exists($key, $user)) $user->address = $address->id;
                unset($address->id);

            } // 2. handle image file
            else if ($key === 'photo') {
                // delete user's old photo
                if ($user->photo !== null && $user->photo !== '/photo/user/default.png') {
                    ImageHandler::deleteImage($user->photo);
                }
                // Store new photo
                $path = ImageHandler::storeImage($user->id, $value, 'user');

                // save image path in user->photo 
                $user->photo = $path;

            
            } // 3. handle others
            else if (in_array($key, $filter)) $user[$key] = $value;
        }
       

        //Create or save user using that infos
        $user->save();

        // attach address to user
        $user->address = $address;

        return $user;
    }
}