@props(['id', 'title' => 'Konfirmasi', 'message', 'method' => 'POST'])

<x-ui.modal :id="$id" :title="$title">
    <p class="text-sm text-gray-600 dark:text-gray-300">
        {{ $message }}
    </p>

    <x-slot:footer>
        <x-ui.button
            type="button"
            variant="secondary"
            @click="$store.modal.close()"
        >
            Batal
        </x-ui.button>

        <form
            :action="$store.modal.payload.action"
            method="POST"
            class="inline"
        >
            @csrf
            <template x-if="$store.modal.payload.method || '{{ $method }}' !== 'POST'">
                <input
                    type="hidden"
                    name="_method"
                    :value="$store.modal.payload.method || '{{ $method }}'"
                >
            </template>

            <x-ui.button
                type="submit"
                variant="danger"
            >
                <span x-text="$store.modal.payload.confirmText || 'Ya, Lanjutkan'"></span>
            </x-ui.button>
        </form>
    </x-slot:footer>
</x-ui.modal>