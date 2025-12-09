@extends('layouts.app')

@section('title', '商品詳細 - ' . $item->name)

@section('css')
<link rel="stylesheet" href="{{ asset('css/item/show.css') }}">
@endsection

@section('content')
<div class="item-detail-page">
    <div class="item-detail-wrapper">
        <div class="item-image-box">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="item-main-image">
        </div>

        <div class="item-info-box">
            <h1 class="item-name">{{ $item->name }}</h1>

            <p class="item-brand">{{ $item->brand_name }}</p>

            <p class="item-price">¥{{ number_format($item->price) }} <span class="tax-info">(税込)</span></p>

            <div class="item-reactions">
                <div class="reaction-item">
                    @auth
                        @if ($isLiked)
                            <form method="post" action="{{ route('item.like.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="reaction-btn liked">
                                    <span class="icon-star">★</span>
                                </button>
                            </form>
                        @else
                            <form method="post" action="{{ route('item.like.store', $item->id) }}">
                                @csrf
                                <button type="submit" class="reaction-btn">
                                    <span class="icon-star">☆</span>
                                </button>
                            </form>
                        @endif
                    @endauth
                    <span class="reaction-count">{{ $likeCount }}</span>
                </div>

                <div class="reaction-item">
                    <a href="#comment-section" class="reaction-btn comment-link">
                        <span class="icon-chat">💬</span>
                    </a>
                    <span class="reaction-count">{{ $commentCount }}</span>
                </div>
            </div>

            <div class="buy-button-area">
                @auth
                    @if(Auth::id() !== $item->user_id && !$item->is_sold)
                        <form method="get" action="{{ route('purchase.show', $item->id) }}">
                            <button type="submit" class="submit-button">購入手続きへ</button>
                        </form>
                    @elseif($item->is_sold)
                        <div class="disabled-button">SOLD OUT</div>
                    @else
                        <div class="disabled-button">出品者です</div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="submit-button login-link">ログインして購入する</a>
                @endauth
            </div>

            <div class="info-section">
                <h3 class="section-title">商品説明</h3>
                <div class="description-text">
                    {!! nl2br(e($item->description)) !!}
                </div>
            </div>

            <div class="info-section">
                <h3 class="section-title">商品の情報</h3>
                <div class="metadata-table">
                    <div class="metadata-row">
                        <span class="metadata-label">カテゴリー</span>
                        <div class="metadata-values">
                            @foreach ($item->categories as $category)
                                <span class="category-tag">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="metadata-row">
                        <span class="metadata-label">商品の状態</span>
                        <span class="metadata-values condition-text">
                            {{ $item->condition->name }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="comment-section" id="comment-section">
                <h3 class="section-title">コメント({{ $commentCount }})</h3>

                <div class="comment-list">
                    @foreach ($item->comments as $comment)
                        <div class="comment-block">
                            <div class="comment-header">
                                <div class="user-avatar">
                                    @if(isset($comment->user->image_url) && $comment->user->image_url)
                                        <img src="{{ $comment->user->image_url }}" alt="">
                                    @else
                                        <div class="no-avatar"></div>
                                    @endif
                                </div>
                                <span class="comment-user-name">{{ $comment->user->name }}</span>
                            </div>
                            <div class="comment-body">
                                {{ $comment->body }}
                            </div>
                        </div>
                    @endforeach
                </div>

                @auth
                    <div class="comment-form-area">
                        <h4 class="form-sub-title">商品へのコメント</h4>
                        <form method="post" action="{{ route('item.comment', $item->id) }}">
                            @csrf
                            <textarea name="comment_body" class="comment-textarea"></textarea>
                            @error('comment_body')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                            <button type="submit" class="submit-button">コメントを送信する</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection