<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;

class ValidLocation implements Rule
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
        $parsedAddress = json_decode($value);
        try {
            return $parsedAddress->street
            && $parsedAddress->ward
            && $parsedAddress->district
            && $parsedAddress->city
            && $parsedAddress->latitude
            && $parsedAddress->longitude;
        } catch (Exception $e) {
            return false;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Vui lòng nhập chính xác địa chỉ';
    }
}
