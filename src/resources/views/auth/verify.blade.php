@extends('layouts.guest')

@section('title', 'メールアドレス認証')
@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email-container">

    <div class="message-box">
        <p>登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>
    </div>

    <div class="main-action-area verify-email__action-main">
        <a href="http://localhost:8025" target="_blank">
            <x-forms.button
                type="button"
                variant="neutral"
                size="medium"
                class="verify-email__button-box"
            >
                認証はこちらから
            </x-forms.button>
        </a>
    </div>

    <div class="resend-section verify-email__action-resend">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-forms.button
                type="submit"
                variant="text"
                class="verify-email__button-text"
            >
                認証メールを再送する
            </x-forms.button>
        </form>
    </div>
</div>
@endsection