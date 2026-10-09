<x-ui.modal id="product-form">
    <x-slot:title>
        <span x-text="$store.modal.payload?.id ? 'Edit Produk' : 'Tambah Produk'"></span>
    </x-slot:title>

    <div
        x-data="{
            id: null,
            name: '',
            category_id: '',
            price: '',
            stock: '',
            is_active: '1',
            currentImage: null,   // URL gambar lama (mode edit)
            preview: null,        // preview file yang baru dipilih
            removeImage: false,

            fill(p) {
                this.id = p?.id ?? null;
                this.name = p?.name ?? '';
                this.category_id = p?.category_id ?? '';
                this.price = p?.price ?? '';
                this.stock = p?.stock ?? '';
                this.is_active = String(p?.is_active ?? 1);
                this.currentImage = p?.image_url ?? null;
                this.preview = null;
                this.removeImage = false;
            },

            pickFile(e) {
                const file = e.target.files[0];
                this.preview = file ? URL.createObjectURL(file) : null;
                if (file) this.removeImage = false;
            },
        }"
        x-init="
            $watch('$store.modal.payload', p => fill(p));

            @if ($errors->any())
                $store.modal.open('product-form', {
                    id: @js(old('_id')),
                    name: @js(old('name')),
                    category_id: @js(old('category_id')),
                    price: @js(old('price')),
                    stock: @js(old('stock')),
                    is_active: @js(old('is_active', 1)),
                });
            @endif
        "
    >
        <form
            method="POST"
            :action="id ? '{{ url('/products') }}/' + id : '{{ route('products.store') }}'"
            enctype="multipart/form-data"
            class="space-y-4"
        >
            @csrf

            <template x-if="id">
                <div>
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_id" :value="id">
                </div>
            </template>

            {{-- Nama --}}
            <div>
                <label for="product-name" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Nama Produk</label>
                <input type="text" id="product-name" name="name" x-model="name" maxlength="150" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label for="product-category" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                <select id="product-category" name="category_id" x-model="category_id" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            {{-- Harga & Stok --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="product-price" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Harga (Rp)</label>
                    <input type="number" id="product-price" name="price" x-model="price" min="0" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('price') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="product-stock" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Stok</label>
                    <input type="number" id="product-stock" name="stock" x-model="stock" min="0" step="1" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('stock') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- status --}}
            <div>
                <label for="product-status" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Tampilkan di Kasir</label>
                <select id="product-status" name="is_active" x-model="is_active"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Produk dengan stok 0 otomatis berstatus Habis.</p>
                @error('is_active') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            {{-- Gambar --}}
            <div>
                <label for="product-image" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Gambar (jpg/png, maks 2MB)</label>

                <div class="flex items-center gap-3 mb-2" x-show="preview || (currentImage && !removeImage)">
                    <img :src="preview ?? currentImage" alt="Preview" class="w-16 h-16 rounded-lg object-cover border border-gray-200 dark:border-gray-600">

                    <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300" x-show="currentImage && !preview">
                        <input type="checkbox" name="remove_image" value="1" x-model="removeImage">
                        Hapus gambar ini
                    </label>
                </div>

                <input type="file" id="product-image" name="image" accept=".jpg,.jpeg,.png" @change="pickFile($event)"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600">
                @error('image') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <x-ui.button type="button" variant="secondary" @click="$store.modal.close()">Batal</x-ui.button>
                <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
            </div>
        </form>
    </div>
</x-ui.modal>