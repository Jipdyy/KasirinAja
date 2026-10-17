@props(['id', 'title' => 'Konfirmasi', 'message', 'method' => 'POST'])

<x-ui.modal :id="$id" :title="$title">
    <p class="text-sm text-gray-600 dark:text-gray-300"
       x-text="$store.modal.payload?.message || @js($message)"></p>

    <x-slot:footer>
        <x-ui.button
            type="button"
            variant="secondary"
            @click="$store.modal.close()"
        >
            Batal
        </x-ui.button>

        <form
            :action="$store.modal.payload?.action"
            method="POST"
            class="inline"
        >
            @csrf
            <template x-if="$store.modal.payload?.method || '{{ $method }}' !== 'POST'">
                <input
                    type="hidden"
                    name="_method"
                    :value="$store.modal.payload?.method || '{{ $method }}'"
                >
            </template>

            <button
                type="submit"
                :class="$store.modal.payload?.tone === 'success'
                    ? 'bg-green-600 hover:bg-green-700 focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600'
                    : 'bg-rose-600 hover:bg-rose-700 focus:ring-rose-300 dark:bg-rose-500 dark:hover:bg-rose-600'"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium text-white focus:ring-4 transition-colors"
            >
                <span x-text="$store.modal.payload?.confirmText || 'Ya, Lanjutkan'"></span>
            </button>
        </form>
    </x-slot:footer>
</x-ui.modal>