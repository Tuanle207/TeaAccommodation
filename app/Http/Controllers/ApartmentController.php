<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Requests\ApartmentModificationRequest;
use App\Http\Requests\CreateApartmentRequest;
use App\Apartment;
use App\Location;
use App\User;

class ApartmentController extends Controller {

    public function createApartment(CreateApartmentRequest $req) {
        // get required apartment's info from $req

        $apartment = new Apartment;

        $filter = [
            'title',
            'description',
            'rent',
            'area',
            'postedBy',
            'phoneContact'
        ];

        $body = $req->all();

        

        // get and save the location
        $location = $req->input('location');
        $storedLocation = Address::create($location);
        $apartment['location'] = $storedLocation->id;
        
        // save new apartment
        $storedApartment = Apartment::create($apartment);

        return response()->json([
            'status' => 'success',
            'data' => $storedApartment
        ], 201);

    }

    public function getPostedApartments(ApartmentModificationRequest $req) {

        $apartments = Apartment::where('postedBy', $req->input('user')->id)->get();
        
        return response()->json([
            'message' => 'success',
            'data' => $apartments
        ], 200);
    }

    public function getApartment(Request $req, $id) {
        $apartment = $req->input('apartment');

        // get address of the apartment
        $address = Address::find($apartment->location);
        unset($address->id);
        $apartment->location = $address;

        // get user postes the apartment
        $user = User::postedBy()->where('id', $apartment->postedBy)->first();
        $apartment->postedBy = $user;

        return response()->json([
            'status' => 'success',
            'data' => $apartment
        ], 200);
    }

    public function updateApartment(ApartmentModificationRequest $req, $id) {

        // get apartment from previous middleware
        $apartment = $req->input('apartment');

        // get required apartment's info from $req
        $input = $req->except(['apartment', 'user']);
        
        $apartmentInfo = [];
        // fitler allowed modified fields
        $fieldsFilter = ['title', 'description', 'rent', 'area', 'phoneContact'];
        
        foreach ($input as $key => $value ) {
            if ($key == 'address') {
                $address = $input['address'];
                Address::where('id', $apartment->location)->update($address);
            } 
            // update other fields
            else if (in_array($key, $fieldsFilter)) {
                $apartmentInfo[$key] = $value;
            }
        }
        
        // save the apartment
        Apartment::where('id', $id)->update($apartmentInfo);

        return response()->json([
            'status' => 'success',
            'data' => null
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
    
    public function deleteApartment(Request $req, $id) {
        Apartment::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'data' => null
        ], 204);
    }
}
