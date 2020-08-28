<?php

namespace App\Http\Requests;

use App\Http\Requests\ApartmentModificationRequest;
use App\Rules\ValidLocation;

class CreateApartmentRequest extends ApartmentModificationRequest
{
   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'location' => ['required', new ValidLocation],
            'rent' => 'required|numeric|min:0',
            'area' => 'required|numeric|min:0',
            'phoneContact' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Bạn cần nhập tiêu đề phòng trọ',
            'description.required' => 'Bạn cần nhập mô tả phòng trọ',
            'location.required' => 'Bạn cần xác định địa chỉ trọ',
            'location' => (new ValidLocation)->message(),
            'rent.required' => 'Bạn cần nhập giá thuê theo tháng',
            'rent.min' => 'Giá thuê không hợp lệ',
            'rent.numeric' => 'Giá thuê không hợp lệ',
            'area.required' => 'Bạn cần nhập diện tích phòng',
            'area.min' => 'Diện tích không hợp lệ',
            'area.numeric' => 'Diện tích không hợp lệ',
            'phoneContact.required' => 'Bạn cần nhập số điện thoại liên hệ'
        ];
    }
}
