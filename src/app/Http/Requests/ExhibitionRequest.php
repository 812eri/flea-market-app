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

    public function attributes(): array
    {
        return [
            'item_image' => '商品画像',
            'name' => '商品名',
            'brand_name' => 'ブランド名',
            'description' => '商品説明',
            'price' => '価格',
            'categories' => 'カテゴリー',
            'condition_id' => '商品の状態',
        ];
    }

    public function message(): array
    {
        return [
            'item_image.required' => '商品画像を選択してください。',
            'name.required' => '商品名を入力してください。',
            'description.required' => '商品説明を入力してください。',
            'price.required' => '価格を入力してください。',
            'categories.required' => 'カテゴリーを選択してください。',
            'condition_id.required' => '商品の状態を選択してください。',

            'item_image.mimes' => '商品画像はjpegまたはpng形式で選択してください。',
            'item_image.max' => '商品画像は2MB以下で選択してください。',

            'name.max' => '商品名は100文字以下で入力してください。',
            'description.max' => '商品説明は255文字以下で入力してください。',

            'price.integer' => '価格は数値で入力してください。',
            'price.min' => '価格は0円以上で入力してください。',

            'categories.min' => 'カテゴリーは1つ以上選択してください。',

            'condition_id.exists' => '選択された商品の状態は無効です。',
        ];
    }
}
