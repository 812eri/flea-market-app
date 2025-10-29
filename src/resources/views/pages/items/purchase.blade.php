@extends('layouts.app')

@section('title', '商品購入')

@section('content')
<div class="purchase-page-container">
    <h2 class="page-title">商品購入</h2>

    <div class="purchase-content-wrapper">
        <div class="purchase-left-section">

            <div class="item-summary-area border-bottom">
                <div class="item-summary-image">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="small-image">
                </div>

                <div class="item-summary-info">
                    <p class="item-summary-name">{{ $item->name }}</p>
                    <p class="item-summary-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            <div class="payment-method-section border-bottom">
                <h3 class="section-title">支払い方法</h3>
                <x-forms.select
                    name="payment_method"
                    :options="[
                        'conbini' => 'コンビニ支払い',
                        'credit' => 'カード支払い']"
                    class="payment-select"
                />
            </div>

            <div class="delivery-address-section border-bottom">
                <div class="section-header">
                    <h3 class="section-title">配送先</h3>
                    <a href="{{ route('address.edit', ['item_id' => $item->id]) }}" class="change-link">変更する</a>
                </div>
            </div>

            <div class="current-address-info">
                <p class="post-code">〒 {{ $address->post_code }}</p>
                <p class="full-address">{{ $address->prefecture . $address->city . $address->street_address . $address->building_name }}</p>
            </div>
        </div>
    </div>

    <div class="purchase-right-section">
        <form method="POST" action="{{ route('purchase.store', $item->id) }}" class="purchase-action-form">
            @csrf

            <div class="summary-box">
                <div class="summary-row">
                    <span>商品代金</span>
                    <span>¥{{ number_format($item->price) }}</span>
                </div>
                <div class="summary-row">
                    <span>支払い方法</span>
                    <span>{{ $selectedPaymentMethod ?? '未選択' }}</span>
                </div>
            </div>

            <div class="final-action-area mt-4">
                <x-forms.button
                    type="submit"
                    variant="primary"
                    size="large"
                    class="w-full"
                >
                    購入する
                </x-forms.button>
            </div>
        </form>
    </div>
</div>
@endsection