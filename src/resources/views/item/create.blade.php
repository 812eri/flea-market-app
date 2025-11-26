@extends('layouts.app')

@section('title', '商品の出品')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
<div class="item-create-container">
    <h2 class="page-title">商品の出品</h2>

    <form method="POST" action="{{ route('item.store') }}" enctype="multipart/form-data" class="item-create-form">
        @csrf

        <h3 class="section-title">商品画像</h3>
        <x-forms.image-upload name="item_image" />

        <div class="two-column-details">
            <div class="two-column-details__left">
                <h3 class="section-title">商品の詳細</h3>
                <x-forms.checkbox-group
                    label="カテゴリ"
                    name="categories"
                    :options="$categories ?? []"
                />

                <x-forms.select
                    label="商品の状態"
                    name="condition_id"
                    id="condition_select"
                    :options="$conditions ?? []"
                />
            </div>
            <div class="two-column-details__right">
                <h3 class="section-title">商品名と説明</h3>
                <x-forms.input label="商品名" name="name" type="text" />
                <x-forms.input label="ブランド名" name="brand_name" type="text" />

                <x-forms.textarea
                    label="商品の説明"
                    name="description"
                    rows="5"
                />
            </div>
        </div>

        <h3 class="section-title border-top">販売価格</h3>
        <x-forms.input
            label="販売価格"
            name="price"
            type="number"
        >
            <span class="price-prefix">¥</span>
        </x-forms.input>

        <div class="form-action-area item-create-form__action-area">
            <x-forms.button
                type="submit"
                variant="primary"
                size="large"
                class="item-create-form__submit"
            >
                出品する
            </x-forms.button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. IDでドロップダウン要素を取得
    const conditionSelect = document.getElementById('condition_select'); 

    // 2. value="" のプレースホルダーオプションを取得
    const placeholderOption = conditionSelect ? conditionSelect.querySelector('option[value=""]') : null;

    if (placeholderOption) {
        // ドロップダウンがクリックされたら (リストが開く前)
        conditionSelect.addEventListener('mousedown', function() {
            // プレースホルダーを非表示にする
            placeholderOption.style.display = 'none';
        });

        // ドロップダウンからフォーカスが外れたら (リストが閉じた後)
        conditionSelect.addEventListener('blur', function() {
            // プレースホルダーを再表示する (リストの上部に表示させるため)
            placeholderOption.style.display = ''; 
        });
    }
});
</script>
@endsection