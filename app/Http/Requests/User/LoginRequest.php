<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class LoginRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'email.email' => 'Email không hợp lệ',
            'email.required' => 'Bạn cần nhập email đăng nhập',
            'password.required' => 'Bạn cần nhập mật khẩu đăng nhập'
        ];
    }
}
