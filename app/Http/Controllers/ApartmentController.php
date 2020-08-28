<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ApartmentModificationRequest;
use App\Http\Requests\CreateApartmentRequest;
use App\Apartment;
use App\Location;

class ApartmentController extends Controller {

    public function createApartment(CreateApartmentRequest $req) {
        // get required apartment's info from $req
        $apartment = [
            'title' => $req->input('title'),
            'description' => $req->input('description'),
            'rent' => $req->input('rent'),
            'area' => $req->input('area'),
            'postedBy' => $req->input('user')->id,
            'phoneContact' => $req->input('phoneContact')
        ];

        // get and save the location
        $location = $req->input('location');
        $storedLocation = Location::create($location);
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
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Phòng trọ này không tồn tại'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $apartment
        ], 200);
    }

    public function updateApartment(ApartmentModificationRequest $req, $id) {

        // check if this apartment exists?
        $apartment = Apartment::find($id);
        if (!$apartment) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Phòng trọ này không tồn tại'
            ], 404);
        }

        // check if this apartment belongs to this user?
        $user = $req->input('user');
        if ($apartment->postedBy != $user->id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Chỉ người đăng phòng trọ này mới có thể cập nhật thông tin phòng trọ'
            ], 404);
        }

        // get required apartment's info from $req
        $input = $req->input();
        
        // return  response()->json([
        //     'status' => 'success',
        //     'data' => $input
        // ], 201);;

        $apartmentInfo = [];
        // fitler allowed modified fields
        $fieldsFilter = ['title', 'description', 'rent', 'area', 'phoneContact'];
        
        foreach ($input as $key => $value ) {
            if ($key == 'location') {
                $location = $input['location'];
                Location::where('id', $apartment->location)->update($location);
            } else if (in_array($key, $fieldsFilter)) {
                $apartmentInfo[$key] = $value;
            }
        }
        
        // save new apartment
        $storedApartment = Apartment::where('id', $id)->update($apartmentInfo);

        return response()->json([
            'status' => 'success',
            'data' => $storedApartment
        ], 201);
    }
    
}
