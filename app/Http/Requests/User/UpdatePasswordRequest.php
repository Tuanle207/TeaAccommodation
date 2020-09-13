<?php

namespace App\Http\Requests;

use  App\Http\Requests\Request;

class UpdatePasswordRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currentPassword' => 'required',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password'
        ];
    }
    public function messages()
    {
        return [
            'currentPassword.required' => 'Bạn cần nhập mật khẩu hiện tại',
            'password.required' => 'Bạn cần nhập mật khẩu mới',
            'passwordConfirm.require' => 'Bạn cần xác nhận mật khẩu mới',
            'passwordConfirm.same' => 'Mật khẩu xác nhận không trùng khớp'
        ];
    }
}
