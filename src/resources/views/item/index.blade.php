@extends('layouts.app')

@section('title', '商品一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item/index.css') }}">
@endsection

@section('content')
<div class="item-list-page">
    <div class="item-list-container">
        @unless (isset($keyword) && $keyword)
            <div class="tab-menu">
                <a href="{{ url('/') }}" class="tab-item {{ $current_tab === 'recommended' ? 'active' : '' }}">おすすめ</a>
                @auth
                    <a href="{{ url('/?tab=mylist') }}" class="tab-item {{ $current_tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
                @endauth
            </div>
        @endunless

        @if (isset($keyword) && $keyword)
            <h1 class="search-result-title">「{{ $keyword }}」の検索結果</h1>
        @endif

        <div class="item-list-content">
            @if ($items->isEmpty())
                <p class="no-items-message">
                    @if (isset($keyword) && $keyword)
                        「{{ $keyword }}」に一致する商品はありません。
                    @else
                        現在、表示できる商品はありません。
                    @endif
                </p>
            @else
                <div class="item-grid">
                    @foreach ($items as $item)
                        <a href="{{ route('item.show', $item->id) }}" class="item-card">
                            <div class="item-image-wrapper">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                                @if($item->status === 'sold')
                                    <div class="sold-label">Sold</div>
                                @endif
                            </div>
                            <p class="item-name">{{ $item->name }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection