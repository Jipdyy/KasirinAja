@props(['variant' => 'primary', 'type' => 'button'])

@php
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-300 dark:focus:ring-blue-800',
        'secondary' => 'bg-white hover:bg-gray-100 text-gray-900 border border-gray-300 focus:ring-gray-200 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600',
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white focus:ring-rose-300 dark:focus:ring-rose-800',
    ];

    $classes = $variants[$variant] ?? $variants['primary'];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium shadow-xs transition-colors focus:outline-none focus:ring-4 disabled:opacity-50 disabled:cursor-not-allowed $classes"]) }}
>
    {{ $slot }}
</button>