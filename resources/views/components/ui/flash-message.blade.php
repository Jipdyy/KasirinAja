@php
    $types = [
        'success' => ['icon' => 'check-circle', 'class' => 'text-emerald-500 bg-emerald-100 dark:bg-emerald-900 dark:text-emerald-300'],
        'error'   => ['icon' => 'x-circle', 'class' => 'text-rose-500 bg-rose-100 dark:bg-rose-900 dark:text-rose-300'],
        'warning' => ['icon' => 'exclamation-triangle', 'class' => 'text-amber-500 bg-amber-100 dark:bg-amber-900 dark:text-amber-300'],
        'info'    => ['icon' => 'information-circle', 'class' => 'text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300'],
    ];
@endphp

@foreach ($types as $type => $config)
    @if (session()->has($type))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition
            class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 border border-gray-200 dark:border-gray-700"
            role="alert"
        >
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg {{ $config['class'] }}">
                <x-ui.icon name="{{ $config['icon'] }}" class="w-5 h-5" />
            </div>
            <div class="ms-3 text-sm font-normal">
                {{ session($type) }}
            </div>
            <button
                type="button"
                @click="show = false"
                class="ms-auto -mx-1.5 -my-1.5 text-gray-400 hover:text-gray-900 rounded-lg p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-700"
                aria-label="Tutup"
            >
                <x-ui.icon name="x-mark" class="w-5 h-5" />
            </button>
        </div>
    @endif
@endforeach