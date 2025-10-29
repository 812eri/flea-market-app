@extends('layouts.app')

@section('title', 'プロフィール')

@section('content')
<div class="profile-page-container">
    @php
        $listType = request('list', 'listed');
    @endphp

    <x-users.profile-info :user="$user" />

    <div class="profile-tabs-menu" border-top>
        <a href="{{ route('profile.show', ['list' => 'listed']) }}"
        class="profile-tab-item {{ $listType === 'listed' ? 'is-active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('profile.show', ['list' => 'purchased']) }}"
        class="tab-item {{ $listType === 'purchased' ? 'is-active' : '' }}">
            購入した商品
        </a>
    </div>

    <div class="item-list-grid-wrapper mt-4">
        @if ($items->isEmpty())
            <p class="no-items-message">{{ $listType === 'listed' ? '出品した商品' : '購入した商品' }}はありません。</p>
        @else
            <div class="item-list-grid item-grid-4-cols">
                @foreach ($items as $item)
                    <x-items.card :item="$item" />
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection