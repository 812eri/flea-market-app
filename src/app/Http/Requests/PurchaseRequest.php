<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseRequest extends FormRequest
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
            'payment_method' => ['required', Rule::in(['conbini', 'card'])],
            'address_id' => ['required', 'exists:addresses,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'payment_method' => '支払い方法',
            'address_id' => '配送先住所',
        ];
    }

    public function message(): array
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '無効な支払い方法が選択されました。',
            'address_id.required' => '配送先住所を選択してください。',
            'address_id.exists' => '選択された配送先住所は登録されていません。',
        ];
    }
}
