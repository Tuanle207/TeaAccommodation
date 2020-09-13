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
            'photo_1' => 'require | image | max:5242880',
            'photo_2' => 'require | image | max:5242880',
            'photo_3' => 'require | image | max:5242880',
            'photo_4' => 'require | image | max:5242880'
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
            'phoneContact.required' => 'Bạn cần nhập số điện thoại liên hệ',
            'photo_1.required' => 'Bạn cần cung cấp đầy đủ hình ảnh phòng trọ',
            'photo_2.required' => 'Bạn cần cung cấp đầy đủ hình ảnh phòng trọ',
            'photo_3.required' => 'Bạn cần cung cấp đầy đủ hình ảnh phòng trọ',
            'photo_4.required' => 'Bạn cần cung cấp đầy đủ hình ảnh phòng trọ',
            'photo_1.image' => 'Định dạng ảnh không hợp lệ hoặc không được hỗ trợ',
            'photo_1.max' => 'Kích thước ảnh tối đa là 5MB',
            'photo_2.image' => 'Định dạng ảnh không hợp lệ hoặc không được hỗ trợ',
            'photo_2.max' => 'Kích thước ảnh tối đa là 5MB',
            'photo_3.image' => 'Định dạng ảnh không hợp lệ hoặc không được hỗ trợ',
            'photo_3.max' => 'Kích thước ảnh tối đa là 5MB',
            'photo_4.image' => 'Định dạng ảnh không hợp lệ hoặc không được hỗ trợ',
            'photo_4.max' => 'Kích thước ảnh tối đa là 5MB'
        ];
    }
}
