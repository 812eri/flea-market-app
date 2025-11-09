@extends('layouts.app')

@section('title', 'プロフィール設定')
@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/edit.css') }}">
@endsection

@section('content')
<div class="profile-settings-container">
    <h2 class="page-title">プロフィール設定</h2>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form">
        @csrf
        @method('patch')

        <div class="profile-image-section">
            <div class="current-image-area">
                <img src="{{ $user->profile_image_url }}" alt="プロフィール画像" class="profile-image">
            </div>

            <div class="image-upload-wrapper">
                <label for="profile_image" class="btn-image-stream_select">
                    画像を投稿する
                </label>
                <input
                    type="file"
                    id="profile_image"
                    name="profile_image"
                    class="hidden-file-input"
                >
            </div>
            @error('profile_image')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

            <x-forms.input
                label="ユーザー名"
                name="user_name"
                type="text"
                value="{{ $user->name }}"
            />

            <x-forms.input
                label="郵便番号"
                name="post_code"
                type="text"
                value="{{ $user->address->post_code ?? '' }}"
            />

            <x-forms.input
                label="都道府県"
                name="prefecture"
                type="text"
                value="{{ $user->address->prefecture ?? '' }}"
            />

            <x-forms.input
                label="市区町村"
                name="city"
                type="text"
                value="{{ $user->address->city ?? '' }}"
            />

            <x-forms.input
                label="住所(番地以降)"
                name="street_address"
                type="text"
                value="{{ $user->address->street_address ?? '' }}"
            />

            <x-forms.input
                label="建物名"
                name="building_name"
                type="text"
                value="{{ $user->address->building_name ?? '' }}"
            />

            <div class="form-action-area mt-8">
                <x-forms.button
                    type="submit"
                    variant="primary"
                    size="large"
                    class="profile-form__submit"
                >
                    更新する
                </x-forms.button>
            </div>
    </form>
</div>
@endsection