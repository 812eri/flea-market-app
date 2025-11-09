@extends('layouts.app')

@section('title', '送付先住所変更')

@section('content')
<div class="address-change-container">
    <h2 class="page-title">住所の変更</h2>

    <form method="post" action="{{ route('purchase.address.update', $item_id) }}" class="address-form">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="post_code" class="form-label">郵便番号</label>
            <input
                id="post_code"
                name="post_code"
                type="text"
                class="form-input"
                value="{{ old('post_code', $address->post_code) }}"
                required
            >
            @error('post_code')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="prefecture" class="form-label">都道府県</label>
            <input
                id="prefecture"
                name="prefecture"
                type="text"
                class="form-input"
                value="{{ old('prefecture', $address->prefecture) }}"
                required
            >
            @error('prefecture')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="city" class="form-label">市区町村</label>
            <input
                id="city"
                name="city"
                type="text"
                class="form-input"
                value="{{ old('city', $address->city) }}"
                required
            >
            @error('city')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="street_address" class="form-label">番地</label>
            <input
                id="street_address"
                name="street_address"
                type="text"
                class="form-input"
                value="{{ old('street_address', $address->street_address) }}"
                required
            >
            @error('street_address')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building_name" class="form-label">建物名</label>
            <input
                id="building_name"
                name="building_name"
                type="text"
                class="form-input"
                value="{{ old('building_name', $address->building_name) }}"
            >
            @error('building_name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-action-area">
            <button
                type="submit"
                class="update-button"
            >
                更新する
            </button>
        </div>
    </form>
</div>
@endsection