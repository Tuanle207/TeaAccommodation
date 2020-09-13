<?php

namespace App\Rules;

use App\Facility;
use Exception;
use Illuminate\Contracts\Validation\Rule;

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
        // facility names
        $facilityNames = [];

        // get allowed facilities in DB and store names in $facilityNames
        $allowedFacilities = Facility::select('id')->get();
        foreach ($allowedFacilities as $key => $value)
            array_push($facilityNames, $value->id);

        // get facilities from request
        $facilities = json_decode($value);

        foreach ($facilities as $key => $value)
            if (in_array($value, $facilityNames));
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
