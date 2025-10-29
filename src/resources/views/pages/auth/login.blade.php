@extends('layouts.guest')

@section('title', 'ログイン')

@section('content')
<div class="auth container">
    <h2 class="page-title">ログイン</h2>

    <form method="POST" action="{{ route ('login') }}" class="auth-form">
        @csrf

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

        <div class="form-action-area mt-8">
            <x-forms.button
                type="submit"
                variant="primary"
                size="large"
                class="w-full"
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