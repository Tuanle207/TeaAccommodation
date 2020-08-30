<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\ValidLocation;

class UpdateUserProfileRequest extends Request
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'phoneNumber' => 'required',
            'passwordConfirm' => 'required',
            'address' => ['required', new ValidLocation]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn cần nhập tên',
            'passwordConfirm.same' => 'Mật khẩu mới không trùng khớp',
            'phoneNumber.required' => "Bạn cần nhập số điện thoại",
            'address.required' => 'Bạn cần nhập địa chỉ',
            'address' => (new ValidLocation)->message()
        ];
    }
}
