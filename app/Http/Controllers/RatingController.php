<?php

namespace App\Http\Controllers;


use App\Http\Requests\Others\RatingRequest;
use App\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    
    public function getRating(Request $req, $id) {
        // get rating based on idApartment and idUser
        $rating = Rating::where('idApartment', $id)->where('ratedBy', $req->input('user')->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $rating
        ], 200);
    }

    public function createOrUpdateRating(RatingRequest $req, $id) {
        
        // create or update if existed rating
        $rating = Rating::where('idApartment', $id)->where('ratedBy', $req->input('user')->id)->first();
        if ($rating) {
            $rating->rating = $req->input('rating');
            $rating->save();
        } else {
            $rating = Rating::create([
                'idApartment' =>  $id,
                'ratedBy' => $req->input('user')->id,
                'rating' => $req->input('rating')
            ]);
        }

        // recalculate the average ratings of the apartment
        $aparment = $req->input('apartment');
        if (!$aparment->rating) {
            $aparment->rating = $rating->rating;
        } else {
            $aparment->rating = Rating::where('idApartment', $id)->avg('rating');
        }
        $aparment->save();

        return response()->json([
            'status' => 'success',
            'data' => $rating
        ], 200);
    }

    public function deleteRating(Request $req, $id) {
        // delete rating
        Rating::where('idApartment', $id)->where('ratedBy', $req->input('user')->id)->delete();

        // recalculate the average ratings of the apartment
        $aparment = $req->input('apartment');
        $aparment->rating = Rating::where('idApartment', $id)->avg('rating');
        $aparment->save();

        return response()->json([
            'status' => 'success',
            'data' => null
        ], 204);
    }
}
