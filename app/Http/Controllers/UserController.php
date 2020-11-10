<?php

namespace App\Http\Controllers;

use App\Address;
use App\Http\Requests\Users\pdateUserProfileRequest;
use App\Utils\ImageHandler;
use App\Location;
use App\Utils\UserModificationHanlder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * * get a particular user's profile infomation
     */
    public function getUserProfile(Request $req) {
        // get user from previous middleware
        $user = $req->user;

        return $this->responseWithUser($user, Address::find($user->id));
    }

    public function updateUserProfile(UpdateUserProfileRequest $req) {
        
        // get user from previous middleware
        $user = $req->user;

        // get passwordConfirm from request
        $passwordConfirm = $req->get('passwordConfirm');

        // check if password confirm is correct?
        if (!Hash::check($passwordConfirm, $user->password)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Mật khẩu không chính xác'
            ], 401);
        };

        // filter for allowed fields (except for photo, address)!
        $filter = ['name', 'phoneNumber'];
        $body = $req->all();

        // save user
        $savedUser = UserModificationHanlder::saveUser($user, $body, $filter);

        return $this->responseWithUser($savedUser, $savedUser->address);
    }

    private function responseWithUser($user, $address) {
        // format address object -- remove id and and attach address to user
        if ($address->id != null) {
            unset($address->id);
        }
        $user->address = $address;

        // response json includes user's data
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
