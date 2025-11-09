<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExhibitionRequest extends FormRequest
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
        $conditionKeys = ['good', 'no_damage', 'some_damage', 'poor'];

        return [
            'item_image' => ['required', 'image', 'mimes:jpeg,png', 'max:2048'],
            'name' => ['required', 'string', 'max:100'],
            'brand_name' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:1',],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'condition_id' => ['required', 'exists:conditions,id'],
        ];
    }
}
