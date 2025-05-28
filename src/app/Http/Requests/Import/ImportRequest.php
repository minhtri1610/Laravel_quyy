<?php

namespace App\Http\Requests\Import;

use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return check_is_admin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:csv,txt',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Vui lòng chọn file',
            'file.mimes' => 'Vui lòng chọn file có định dạng .csv',
        ];
    }
}
