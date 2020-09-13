<?php

namespace App\Rules;

use App\Parameter;
use Illuminate\Contracts\Validation\Rule;

use function GuzzleHttp\json_decode;

class ValidFacility implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // get allowed facilities in DB and store names in $facilityNames
        $facilityNames = json_decode(Parameter::where('name', 'facilities')->first()->value);

        // get facilities from request
        $facilities = json_decode($value);

        // check if all input facilities are correct?
        foreach ($facilities as $key => $fac)
            if (!in_array($fac, $facilityNames))
                return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Vui lòng chọn cơ sở vật chất hợp lệ';
    }
}
