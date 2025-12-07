@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h2>🎉ご購入が完了しました！</h2>
    <p>引き続きフリマサイトでのお買い物をお楽しみください。</p>
    <a href="{{ url('/') }}" class="btn btn-primary">商品一覧へ戻る</a>
</div>
@endsection
