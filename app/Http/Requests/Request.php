<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $messages = $validator->errors();
        throw new HttpResponseException(response()->json([
            'status' => 'fail',
            'messages' => $messages
        ], 400));
    }
}
