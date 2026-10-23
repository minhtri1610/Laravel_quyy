<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserFromAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required',
            'email' => 'nullable|email',
        ] + self::avatarRules();
    }

    public function messages(): array
    {
        return self::avatarMessages();
    }

    /**
     * Dùng chung cho trang thêm mới và trang chỉnh sửa Phật tử
     */
    public static function avatarRules(): array
    {
        return [
            'avatar_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public static function avatarMessages(): array
    {
        return [
            'avatar_file.image' => 'Ảnh đại diện phải là file hình ảnh.',
            'avatar_file.mimes' => 'Ảnh đại diện chỉ nhận định dạng JPG, PNG hoặc WEBP.',
            'avatar_file.max' => 'Ảnh đại diện không được vượt quá 5MB.',
            'avatar_file.uploaded' => 'Không tải được ảnh lên, vui lòng chọn ảnh nhỏ hơn.',
        ];
    }
}
