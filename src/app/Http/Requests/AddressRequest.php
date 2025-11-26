<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'post_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'street_address' => ['required', 'string', 'max:100'],
            'building_name' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'post_code' => '郵便番号',
            'street_address' => '住所',
            'building_name' => '建物名',
        ];
    }

    public function messages(): array
    {
        return [
            'post_code.required' => '郵便番号を入力してください。',
            'post_code.regex' => '郵便番号はハイフンを含めた形式(XXX-XXXX)で入力してください。',

            'street_address.required' => '住所を入力してください。',
        ];
    }
}
