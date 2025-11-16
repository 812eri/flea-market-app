@extends('layouts.guest')

@section('title', '会員登録')
@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/auth/register.css') }}">
@endsection

@section('content')
<div class="registration-container">
    <h2 class="page-title">会員登録</h2>

    <form method="post" action="{{ route('register') }}" class="registration-form">
        @csrf

        <x-forms.input
            label="ユーザー名"
            name="name"
            type="text"
            placeholder="ユーザー名"
            />

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

        <x-forms.input
            label="確認用パスワード"
            name="password_confirmation"
            type="password"
            placeholder="確認用パスワード"
            />

        <div class="form-action-area">
            <x-forms.button
                type="submit"
                variant="primary"
                size="medium"
                class="registration-form__submit"
                >
                登録する
            </x-forms.button>
        </div>
    </form>

    <div class="registration-form__link-area">
        <a href="{{ route('login') }}">
            <x-forms.button type="button" variant="text">
                ログインはこちら
            </x-forms.button>
        </a>
    </div>
</div>
@endsection