@extends('layouts.guest')

@section('title', 'ログイン')
@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/auth/login.css') }}">
@endsection

@section('content')
<div class="auth container">
    <h2 class="page-title">ログイン</h2>

    <form method="POST" action="{{ route ('login') }}" class="auth-form">
        @csrf

        @error('email')
            @if ($message === trans('auth.failed'))
                <p class="error-message auth-form__general-error">ログイン情報が登録されていません</p>
            @else
                <p class="error-message">{{ $message }}</p>
            @endif
        @enderror

        <x-forms.input
            label="メールアドレス"
            name="email"
            type="email"
            placeholder="メールアドレス"
        />

        <x-forms.input
            label="パスワード"
            name="password"
            type="password"
            placeholder="パスワード"
        />

        <div class="form-action-area auth-form__action">
            <x-forms.button
                type="submit"
                variant="primary"
                size="large"
                class="auth-form__submit"
            >
                ログインする
            </x-forms.button>
        </div>
    </form>

    <div class="auth-link">
        <a href="{{ route('register') }}">
            <x-forms.button
                type="button"
                variant="text"
            >
                会員登録はこちら
            </x-forms.button>
        </a>
    </div>
</div>
@endsection