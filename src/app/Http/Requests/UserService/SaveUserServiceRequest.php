<?php
declare(strict_types=1);

namespace App\Http\Requests\UserService;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

        ];
    }

    public function attributes()
    {
        return [

        ];
    }

    public function messages()
    {
        $message = [

        ];
        return array_merge(parent::messages(), $message);
    }
}
