<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png'],
            'user_name' => ['required', 'string', 'max:20'],
            'post_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'prefecture' => ['required', 'string', 'max:20'],
            'city' => ['required','string', 'max:50'],
            'street_address' => ['required','string', 'max:100'],
            'building_name' => ['nullable', 'string', 'max:100'],
        ];
    }
}
