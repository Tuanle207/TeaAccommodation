<?php

namespace App\Http\Controllers;

use App\Parameter;
use stdClass;

class ParameterController extends Controller
{
    
    public function getParams() {
        // get rating based on idApartment and idUser
        $params = Parameter::get();
        
        $paramsForReturn = new stdClass();

        foreach ($params as $i => $value) {
            $key = $value->name;
            $paramsForReturn->$key = json_decode($value->value); 
        }

        return response()->json([
            'status' => 'success',
            'data' => $paramsForReturn
        ], 200);
    }
}
