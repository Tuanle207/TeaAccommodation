<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SignupRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password',
            'phoneNumber' => 'required'
        ];
    }
    public function messages()
    {
        return [
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
