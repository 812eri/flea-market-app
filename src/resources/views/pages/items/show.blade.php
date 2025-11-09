@extends('layouts.app')

@section('title', '商品詳細 - ' . $item->name)
@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/items/show.css') }}">
@endsection

@section('content')
<div class="item-detail-page container">
    <div class="item-detail-main">
        <div class="item-image-area">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="item-main-image">
        </div>

    <div class="item-info-area">
        <h1 class="item-name">{{ $item->name }}</h1>
        <p class="item-brand">ブランド名: {{ $item->brand_name }}</p>
        <p class="item-price">¥{{ number_format($item->price) }} <span class="tax-info">(税込)</span></p>

        <div class="item-reactions">
            @auth
            @if ($isLiked)
                <form method="post" action="{{ route('item.like.destroy', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="like-button liked">
                        <span class="like-icon">★</span>
                    </button>
                </form>
                @else
                <form method="post" action="{{ route('item.like.store', $item->id) }}">
                    @csrf
                    <button type="submit" class="like-button">
                        <span class="like-icon">☆</span>
                    </button>
                    </form>
                @endif
            @endauth
            <span class="like-count">{{ $likeCount }}</span>
            <span class="comment-count">💬 {{ $commentCount }}</span>
        </div>

        <div class="buy-button-wrapper">
            @auth
                @if(Auth::id() !== $item->user_id && !$item->is_sold)
                    <form method="get" action="{{ route('purchase.show', $item->id) }}">
                        <button
                            type="submit"
                            class="buy-button"
                        >
                            購入手続きへ
                        </button>
                    </form>
                @elseif($item->is_sold)
                    <p class="is-sold-message">SOLD OUT</p>
                @else
                    <p class="is-seller-message">ご自身が出品した商品です。</p>
                @endif
            @else
                <p class="login-required-message">購入するにはログインが必要です。</p>
            @endauth
        </div>

        <h2 class="section-title">商品説明</h2>
        <div class="item-description-body">
            <p>{{ $item->description }}</p>
        </div>

        <h2 class="section-title">商品の情報</h2>
        <div class="item-metadata-list">
            <div class="metadata-row">
                <span class="metadata-label">カテゴリー</span>
                <div class="metadata-tags">
                    @foreach ($item->categories as $category)
                        <span class="metadata-tag tag-category">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            </div>
                <div class="metadata-row">
                    <span class="metadata-label">商品の状態</span>
                    <span class="metadata-tag tag-condition">
                        {{ $item->condition->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="comment-section">
        <h2 class="section-title">コメント({{ $commentCount }})</h2>

        <div class="comment-list">
            @foreach ($item->comments as $comment)
            <div class="comment-item">
                <p class="comment-user">{{ $comment->user->name }}</p>
                <p class="comment-body">{{ $comment->body }}</p>
                <p class="comment-timestamp">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
        @auth
        <h3 class="comment-form-title">商品へのコメント</h3>
        <form method="post" action="{{ route('item.comment', $item->id) }}" class="comment-form">
            @csrf

            <textarea
                name="comment_body"
                rows="5"
                class="comment-textarea"
                placeholder="コメントを入力してください。"
            ></textarea>
            @error('comment_body')
                <p class="error-message">{{ $message }}</p>
            @enderror

            <div class="comment-action-area">
                <button
                    type="submit"
                    class="comment-submit-button"
                >
                    コメントを送信する
                </button>
            </div>
        </form>
        @endauth
    </div>
</div>
@endsection