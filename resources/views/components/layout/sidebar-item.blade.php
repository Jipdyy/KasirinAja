@props(['href', 'label', 'icon' => null])

@php
    $isActive = request()->url() === $href;
@endphp

<a
    href="{{ $href }}"
    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
           {{ $isActive
                ? 'bg-blue-50 text-blue-600 dark:bg-gray-700 dark:text-white'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}"
>
    <span>{{ $label }}</span>
</a>