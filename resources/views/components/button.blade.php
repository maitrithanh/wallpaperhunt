@props([
    'variant' => 'primary', // primary, dark, outline, secondary
    'type' => 'button',     // button, submit
    'href' => null,
])

@php
    $variantClasses = [
        'primary' => 'common-button-primary',
        'blue' => 'common-button-primary',
        'dark' => 'common-button-dark',
        'secondary' => 'common-button-dark',
        'outline' => 'common-button-outline',
    ][$variant] ?? 'common-button-primary';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "common-button $variantClasses"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "common-button $variantClasses"]) }}>
        {{ $slot }}
    </button>
@endif
