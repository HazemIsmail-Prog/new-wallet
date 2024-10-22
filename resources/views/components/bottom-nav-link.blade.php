@props(['active', 'title', 'icon'])

@php
    $classes =
        $active ?? false
            ? 'w-full h-full flex flex-col gap-1 items-center justify-center primary-bg white-text rounded-lg'
            : 'w-full h-full flex flex-col gap-1 items-center justify-center';
@endphp

<a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    <x-dynamic-component :component="'svgs.' . $icon" class="w-6 h-6" />
    <div class=" text-xs">{{ $title }}</div>
</a>
