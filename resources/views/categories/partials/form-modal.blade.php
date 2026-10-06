{{-- resources/views/categories/partials/form-modal.blade.php --}}

<div
    x-data="{
        form: { id: null, name: '', description: '' },

        init() {
            this.$watch('$store.modal.payload', (payload) => {
                this.form = {
                    id: payload.id ?? null,
                    name: payload.name ?? '',
                    description: payload.description ?? ''
                };
            });
        },

        get actionUrl() {
            return this.form.id
                ? `/categories/${this.form.id}`
                : '/categories';
        },

        get modalTitle() {
            return this.form.id ? 'Edit Kategori Produk' : 'Tambah Kategori Produk';
        }
    }"
>
    <x-ui.modal id="category-form">

        <x-slot:title>
            <span x-text="modalTitle"></span>
        </x-slot:title>

        <form
            id="category-modal-form"
            :action="actionUrl"
            method="POST"
            class="space-y-4"
        >
            @csrf

            <template x-if="form.id">
                <input type="hidden" name="_method" value="PUT">
            </template>

            {{-- Nama --}}
            <div>
                <label for="category_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Kategori
                </label>
                <input
                    type="text"
                    id="category_name"
                    name="name"
                    x-model="form.name"
                    required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Masukkan nama kategori..."
                >
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="category_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Deskripsi
                </label>
                <textarea
                    id="category_description"
                    name="description"
                    x-model="form.description"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Masukkan deskripsi singkat..."
                ></textarea>
            </div>
        </form>

        <x-slot:footer>
            <x-ui.button type="button" variant="secondary" @click="$store.modal.close()">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" form="category-modal-form" variant="primary">
                Simpan
            </x-ui.button>
        </x-slot:footer>

    </x-ui.modal>
</div>