@props(['user'])

<div {{ $attributes->merge(['class' => 'profile-header-area']) }}>

    <div class="user-identity">
        <div class="profile-image-wrapper">
            <img src="{{ $user->profile_image_url }}" alt="プロフィール画像" class="profile-image-large">
        </div>
        <h1 class="user-name">{{ $user->name }}</h1>
    </div>

    <div class="profile-actions">
        <a href="{{ route('profile.edit') }}">
            <x-forms.button
                type="button"
                variant="secondary-outline"
                size="small"
            >
                プロフィールを編集
            </x-forms.button>
        </a>
    </div>
</div>