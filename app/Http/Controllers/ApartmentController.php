<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Requests\Apartments\ApartmentModificationRequest;
use App\Http\Requests\Apartments\CreateApartmentRequest;
use App\Apartment;
use App\Http\Requests\Apartments\UpdateApartmentRequest;
use App\User;
use App\Utils\ApartmentModificationHandler;
use App\Utils\ApiFeaturesHandler;

class ApartmentController extends Controller {

    public function createApartment(CreateApartmentRequest $req) {
        // field filter
        $filter = [
            'title',
            'description',
            'rent',
            'area',
            'postedBy',
            'phoneContact'
        ];

        // get required apartment's info from $req
        $body = $req->all();
        $body['postedBy'] = $req->input('user')->id;
        
        // save new apartment
        $storedApartment = ApartmentModificationHandler::saveApartment(new Apartment, $body, $filter);

        // get user posted the apartment
        $user = self::filterUser($req->input('user'));;

        return response()->json([
            'status' => 'success',
            'data' => $storedApartment
        ], 201);
    }

    public function getPostedApartments(ApartmentModificationRequest $req) {
        
        $apiHandler = new ApiFeaturesHandler(
            Apartment::query(),
            (object)$req->all(),
            'apartment',
            $req->input('user')->id
        );

        $result = $apiHandler->useIdentifier()->sort()->limitFields()->paginate()->populate()->getWithMetadata();

        return response()->json([
            'message' => 'success',
            'meta' => $result->meta,
            'data' => $result->data
        ], 200);
    }

    public function getApartments(Request $req) {

        $apiHandler = new ApiFeaturesHandler(
            Apartment::query(),
            (object)$req->all(),
            'apartment'
        );

        $result = $apiHandler->filter()->sort()->limitFields()->paginate()->populate()->getWithMetadata();

        return response()->json([
            'message' => 'success',
            'meta' => $result->meta,
            'data' => $result->data
        ], 200);
    }

    public function getApartment(Request $req) {

        // get apartment from previous middleware 
        $apartment = $req->input('apartment');

        // get address of the apartment
        $address = Address::find($apartment->address);
        unset($address->id);
        $apartment->address = $address;

        // get user posted the apartment
        $user = User::postedBy()->where('id', $apartment->postedBy)->first();
        $apartment->postedBy = $user;

        return response()->json([
            'status' => 'success',
            'data' => $apartment
        ], 200);
    }

    public function updateApartment(UpdateApartmentRequest $req) {

        // get apartment from previous middleware
        $apartment = $req->input('apartment');

        // field filter
        $filter = [
            'title',
            'description',
            'rent',
            'area',
            'phoneContact'
        ];

        // get required apartment's info from $req
        $body = $req->all();
        
        // save new apartment
        $storedApartment = ApartmentModificationHandler::saveApartment($apartment, $body, $filter);


        // get user posted the apartment
        $user = self::filterUser($req->input('user'));
        
        foreach ($storedApartment as $index => $apartment)
            $storedApartment->postedBy = $user;

        return response()->json([
            'status' => 'success',
            'data' => $storedApartment
        ], 200);
    }

    public function activateAvailabilityMode(Request $req, $id) {
        // còn phòng
        Apartment::where('id', $id)->update(['status' => 'còn phòng']);

        return response()->json([
            'status' => 'success',
            'data' => null
        ], 200);
    }

    public function deactivateAvailabilityMode(Request $req, $id) {
        // hết phòng
        Apartment::where('id', $id)->update(['status' => 'hết phòng']);

        return response()->json([
            'status' => 'success',
            'data' => null
        ], 200);
    }
    
    public function deleteApartment(Request $req) {

        $apartment = $req->input('apartment');
        // delete appartment
        ApartmentModificationHandler::deleteApartment($apartment);

        return response()->json([
            'status' => 'success',
            'data' => null
        ], 204);
    }

    private static function filterUser($srcUser) {
        $user = (object)[];
        $user->id = $srcUser->id;
        $user->name = $srcUser->name;
        $user->photo = $srcUser->photo;
        return $user;
    }
}
