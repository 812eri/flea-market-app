@props([
    'type' => 'submit',
    'variant' => 'primary',
    'size' => 'medium'
    ])

<button
    {{ $attributes->merge([
        'type' => $type,
        'class' => "c-button c-button--{$variant} c-button--{$size}"
        ]) }}
>
    {{ $slot }}
</button>