<?php
declare(strict_types=1);

namespace App\Http\Requests\TemporaryUserService;

use Illuminate\Foundation\Http\FormRequest;

class SaveTemporaryUserServiceRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'unique:temporary_users|nullable|email|max:255',
            'phone_number' => 'required|string|max:15',
            'birth_date' => 'nullable|date',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Vui lòng nhập họ và tên.',
            'full_name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'full_name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
    
            'gender.in' => 'Giới tính không hợp lệ.',
    
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'email.nullable' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
    
            'phone_number.required' => 'Vui lòng nhập số điện thoại.',
            'phone_number.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone_number.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
    
            'birth_date.date' => 'Ngày sinh không hợp lệ.',
    
            'province.string' => 'Tỉnh/Thành phố phải là chuỗi ký tự.',
            'province.max' => 'Tỉnh/Thành phố không được vượt quá 255 ký tự.',
    
            'district.string' => 'Quận/Huyện phải là chuỗi ký tự.',
            'district.max' => 'Quận/Huyện không được vượt quá 255 ký tự.',
    
            'ward.string' => 'Phường/Xã phải là chuỗi ký tự.',
            'ward.max' => 'Phường/Xã không được vượt quá 255 ký tự.',
    
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
    
            'note.string' => 'Ghi chú phải là chuỗi ký tự.',
        ];
    }
}
