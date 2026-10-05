@props(['id', 'title'])

<div
    x-show="$store.modal.current === '{{ $id }}'"
    x-transition.opacity
    @keydown.escape.window="$store.modal.close()"
    @click.self="$store.modal.close()"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
    style="display: none;"
>
    <div
        x-transition
        class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
    >
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $title }}
            </h3>
            <button
                type="button"
                @click="$store.modal.close()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                aria-label="Tutup"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-5">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>