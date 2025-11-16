@extends('layouts.app')

@section('title', 'プロフィール')
@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/index.css') }}">
@endsection

@section('content')
<div class="profile-page-container">
    @php
        $listType = request('page', 'listed');
    @endphp

    <x-users.profile-info :user="$user" />

    <div class="profile-edit-link-wrapper profile-page__edit-link-wrapper">
        <a href="{{ route('profile.edit') }}" class="profile-edit-link c-button c-button--outline c-button--small">
            プロフィールを編集
        </a>
    </div>

    <div class="profile-tabs-menu mt-8">
        <a href="{{ route('profile.show', ['page' => 'listed']) }}"
        class="profile-tab-item {{ $listType === 'listed' ? 'is-active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('profile.show', ['page' => 'purchased']) }}"
        class="profile-tab-item {{ $listType === 'purchased' ? 'is-active' : '' }}">
            購入した商品
        </a>
    </div>

    <div class="item-list-grid-wrapper profile-page__grid-wrapper">
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