<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SignupRequest extends Request
{
 
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users|email',
            'name' => 'required',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password',
            'phoneNumber' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'email.email' => 'Email không hợp lệ',
            'email.required' => 'Bạn cần nhập email',
            'email.unique' => 'Email này đã được sử dụng rồi',
            'name.required' => 'Bạn cần nhập tên',
            'password.required' => 'Bạn cần nhập mật khẩu mới',
            'passwordConfirm.required' => 'Bạn cần xác nhận mật khẩu mới',
            'passwordConfirm.same' => 'Mật khẩu mới không trùng khớp',
            'phoneNumber.required' => "Bạn cần nhập số điện thoại"
        ];
    }
}
