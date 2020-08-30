<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Utils\ImageHandler;

class UserController extends Controller
{
    public function getUserProfile(Request $req) {
        // get user from previous middleware
        $user = $req->user;

        return $this->responseWithUser($user, Location::find($user->id));
    }

    public function updateUserProfile(UpdateUserProfileRequest $req) {
        
        // get user from previous middleware
        $user = $req->user;

        // get passwordConfirm from request
        $passwordConfirm = $req->get('passwordConfirm');

        // check password confirm
        if (!Hash::check($passwordConfirm, $user->password)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Mật khẩu không chính xác'
            ], 401);
        };

        // filter for allowed fields!
        $filter = ['name', 'phoneNumber'];
        $body = $req->except(['user', 'password']);

        // temp object for store location option
        $location = null;

        // loop through body to handle updating
        foreach ($body as $key => $value) {
            // handle image file
            if ($key == 'photox') {
                // delete user's old photo
                if ($user->photo != '/photo/user/default.png' && $user->photo != null) {
                    ImageHandler::deleteImage($user->photo);
                }
                // Store new photo
                $path = ImageHandler::storeImage($user->id, $value, 'user');
                
                // save image path in user->photo 
                $user->photo = $path;

            // handle address
            } else if ($key == 'address') {
                // parse json stringified
                $parsedAddress = json_decode($value);

                // user have already had some address?
                $location = $user->address != null ? $location = Location::find($user->address) : new Location;
                // update address info
                $location->latitude = $parsedAddress->latitude;
                $location->longitude = $parsedAddress->longitude;
                $location->description = $parsedAddress->description;
                // save
                $location->save();
                // user have not any address yet?
                if ($user->address == null) $user->address = $location->id;
            
            // handle others
            } else if (in_array($key, $filter)) $user[$key] = $value;
        }

        // save user
        $user->save();

        return $this->responseWithUser($user, $location);
    }

    private function responseWithUser($user, $address) {
        // format address object -- remove id and and attach address to user
        unset($address->id);
        $user->address = $address;

        // response json includes user's data
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
