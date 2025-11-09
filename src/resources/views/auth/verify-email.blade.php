@extends('layouts.app')

@section('title', 'メールアドレス認証')

@section('content')
<div class="verify-email-container centered-content">

    <div class="message-box text-center">
        <p>登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>
    </div>

    <div class="main-action-area mt-8">
        <a href="http://localhost:8025" target="_blank">
            <x-forms.button
                type="button"
                variant="neutral"
                size="medium"
            >
                認証はこちらから
            </x-forms.button>
        </a>
    </div>

    <div class="resend-section mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-forms.button
                type="submit"
                variant="text"
            >
                認証メールを再送する
            </x-forms.button>
        </form>
    </div>
</div>
@endsection