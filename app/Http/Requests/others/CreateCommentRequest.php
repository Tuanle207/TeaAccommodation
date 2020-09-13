<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateCommentRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required | max:200',
            'photo' => 'image | max:5242880'
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'Bạn cần nhập nội dung bình luận',
            'text.max' => 'Bình luận chỉ có thể chứa tối đa 200 kí tự',
            'photo.image' => 'Định dạng ảnh không hợp lệ',
            'photo.max' => 'Kích thước ảnh tối đa là 5MB'
        ];
    }
}
