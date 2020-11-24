<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Rules\ValidAddress;

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
            'address' => ['required', new ValidAddress],
            'photo' => 'image | max:5242880'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn cần nhập tên',
            'passwordConfirm.required' => 'Bạn cần nhập mật khẩu xác nhận',
            'phoneNumber.required' => "Bạn cần nhập số điện thoại",
            'address.required' => 'Bạn cần nhập địa chỉ',
            'address' => (new ValidAddress)->message(),
            'photo.image' => 'Định dang ảnh không hợp lệ hoặc không được hỗ trợ',
            'photo.max' => 'Kích thước ảnh tối đa là 5MB'
        ];
    }
}
