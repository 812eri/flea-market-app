@extends('layouts.app')

@section('title', '商品一覧')
@section('css')
<link rel="stylesheet" href="{{ asset('css/item/index.css') }}">
@endsection

@section('content')
<div class="item-list-page-container">
    @unless (isset($keyword) && $keyword)
        <div class="tab-menu-wrapper">
            <a href="{{ url('/') }}" class="tab-item {{ $current_tab === 'recommended' ? 'is-active' : '' }}">おすすめ</a>
            @auth
            <a href="{{ url('/?tab=mylist') }}" class="tab-item {{ $current_tab === 'mylist' ? 'is-active' : '' }}">マイリスト</a>
            @endauth
        </div>
    @endunless

    <div class="item-list-grid-wrapper">
        @if (isset($keyword) && $keyword)
            <h3 class="search-result-title">「{{ $keyword }}」の検索結果</h3>
        @endif

        @if ($items->isEmpty())
            <p class="no-items-message">
                @if (isset($keyword) && $keyword)
                「{{ $keyword }}」に一致する商品はありません。
                @else
                    現在、表示できるものはありません
                @endif
            </p>
        @else
            <div class="item-list-grid">
                @foreach ($items as $item)
                    <x-items.card :item="$item" />
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection