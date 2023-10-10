<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAppsEvoriaRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone' => 'required|unique:user_apps,phone',
            'email' => 'required|unique:user_apps,email',
        ];
    }
}
