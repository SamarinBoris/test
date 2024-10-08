<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'last_name' => ['required', 'string', 'max:40'],
            'name' => ['required', 'string', 'max:40'],
            'middle_name' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:40'],
            'phone' => ['required', 'phone:mobile', 'max:20'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }
}
