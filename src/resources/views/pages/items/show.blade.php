@extends('layouts.app')

@section('title', '商品詳細 - ' . $item->name)

@section('content')
<div class="item-detail-page">
    <div class="item-detail-main">
        <div class="item-image-area">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="item-main-image">
        </div>

    <div class="item-info-area">
        <h1 class="item-name">{{ $item->name }}</h1>
        <p class="item-brand">ブランド名: {{ $item->brand_name }}</p>
        <p class="item-price">¥{{ number_format($item->price) }} <span class="tax-info">(税込)</span></p>

        <div class="item-reactions">
            <span class="like-count">☆ {{ $item->likeCount }}</span>
            <span class="comment-count">💬 {{ $item->commentCount }}</span>
        </div>

        <div class="buy-button-wrapper">
            @auth
                @if(Auth::id() !== $item->user_id)
                    <form method="get" action="/purchase/{{ $item->id }}">
                        <x-forms.button
                            type="submit"
                            variant="primary"
                            size="large"
                        >
                            購入手続きへ
                        </x-forms.button>
                    </form>
                @else
                    <p class="is-seller-message">ご自身が出品した商品です。</p>
                @endif
            @endauth
        </div>

        <h2 class="section-title">商品説明</h2>
        <div class="item-description-body">
            <p>{{ $item->description }}</p>
        </div>

        <h2 class="section-title">商品の情報</h2>
        <div class="item-metadata">
            <p>カテゴリー
                @foreach ($item->categories as $category)
                    <x-items.tag type="category">{{ $category->name }}</x-items.tag>
                @endforeach
            </p>
            <p>商品の状態
                <x-items.tag type="condition">{{ $item->getConditionLabel() }}</x-items.tag>
            </p>
        </div>
    </div>
    </div>

    <div class="comment-section">
        <h2 class="section-title">コメント({{ $item->commentCount }})</h2>

        @foreach ($item->comments as $comment)
        <div class="comment-item">
            <p class="comment-user">{{ $comment->user->name }}</p>
            <p class="comment-body">{{ $comment->body }}</p>
        </div>
        @endforeach

        @auth
        <h3 class="section-subtitle">商品へのコメント</h3>
        <form method="post" action="{{ route('item.comment', $item) }}" class="comment-form">
            @csrf

            <x-forms.textarea
                name="comment_body"
                rows="5"
                placeholder="コメントを入力してください。"
            />
            <div class="comment-action-area">
                <x-forms.button
                    type="submit"
                    variant="primary"
                    size="medium"
                >
                    コメントを送信する
                </x-forms.button>
            </div>
        </form>
        @endauth
    </div>
</div>
@endsection