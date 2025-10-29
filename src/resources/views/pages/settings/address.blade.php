@extends('layouts.app')

@section('title', '送付先住所変更')

@section('content')
<div class="address-change-container">
    <h2 class="page-title">住所の変更</h2>

    <form method="post" action="{{ route('address.update') }}" class="address-form">
        @csrf
        @method('patch')
        <x-forms.input
            label="郵便番号"
            name="post_code"
            type="text"
            value="{{ $user->post_code }}"
        />

        <x-forms.input
            label="住所"
            name="address"
            type="text"
            value="{{ $user->address }}"
        />

        <x-forms.input
            label="建物名"
            name="building_name"
            type="text"
            value="{{ $user->building_name }}"
        />

        <div class="form-action-area" mt-8>
            <x-forms.button
                type="submit"
                variant="primary"
                size="large"
                class="w-full"
            >
                更新する
            </x-forms.button>
        </div>
    </form>
</div>
@endsection