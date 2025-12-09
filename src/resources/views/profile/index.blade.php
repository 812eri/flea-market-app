@extends('layouts.app')

@section('title', 'プロフィール')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/index.css') }}">
@endsection

@section('content')
<div class="profile-page">
    <div class="profile-container">
        @php
            $page = request('page', 'sell');
        @endphp

        <div class="profile-header">
            <div class="profile-info-left">
                <div class="profile-icon">
                    @if(isset($user->profile_image_url) && $user->profile_image_url)
                        <img src="{{ $user->profile_image_url }}" alt="プロフィール画像">
                    @else
                        <div class="no-icon"></div>
                    @endif
                </div>
                <h1 class="profile-name">{{ $user->name }}</h1>
            </div>

            <div class="profile-info-right">
                <a href="{{ route('profile.edit') }}" class="edit-button">プロフィールを編集</a>
            </div>
        </div>

        <div class="profile-tabs">
            <a href="{{ route('profile.show', ['page' => 'sell']) }}"
                class="tab-item {{ $page === 'sell' ? 'active' : '' }}">
                出品した商品
            </a>
            <a href="{{ route('profile.show', ['page' => 'buy']) }}"
                class="tab-item {{ $page === 'buy' ? 'active' : '' }}">
                購入した商品
            </a>
        </div>

        <div class="item-list-container">
            @if ($items->isEmpty())
                <div class="no-items-message">
                    <p>{{ $page === 'sell' ? '出品した商品' : '購入した商品' }}はありません。</p>
                </div>
            @else
                <div class="item-grid">
                    @foreach ($items as $item)
                        <a href="{{ route('item.show', $item->id) }}" class="item-card">
                            <div class="item-image-wrapper">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                                @if($item->is_sold)
                                    <div class="sold-label">SOLD</div>
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