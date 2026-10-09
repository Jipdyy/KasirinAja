<x-layouts.app title="Produk">

    {{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-primary-600">Dashboard</a>
            <span>/</span>
            <span class="text-gray-900 dark:text-white font-medium">Produk</span>
        </nav>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
            Produk
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Kelola daftar menu, harga, dan stok kafetaria.
        </p>
    </div>

    @can('products.create')
        <button
            type="button"
            @click="$store.modal.open('product-form', {})"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-xs transition-colors shrink-0"
        >
            <x-ui.icon name="plus" class="w-4 h-4" />
            Tambah Produk
        </button>
    @endcan
</div>

    <div class="relative overflow-hidden bg-white shadow-xs dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">

        {{-- Toolbar --}}
        <form method="GET" class="p-4 flex flex-col md:flex-row gap-3 border-b border-gray-200 dark:border-gray-700">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama produk..."
                class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

            <select name="category"
                class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="status"
                class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua Status</option>
                <option value="available" @selected(request('status') === 'available')>Tersedia</option>
                <option value="unavailable" @selected(request('status') === 'unavailable')>Habis</option>
            </select>

            <x-ui.button type="submit" variant="primary">Cari</x-ui.button>
        </form>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Gambar</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Stok</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            {{-- Gambar --}}
                            <td class="px-4 py-3">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                        class="h-10 w-10 rounded-lg object-cover">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200 text-sm font-semibold text-gray-500 dark:bg-gray-600 dark:text-gray-300">
                                        {{ strtoupper(substr($product->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->category->name }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->formatted_price }}</td>

                            <td class="px-4 py-3 font-medium
                                {{ $product->stock === 0 ? 'text-red-600 dark:text-red-400' : ($product->stock <= 5 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-600 dark:text-gray-300') }}">
                                {{ $product->stock }}
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $product->is_available
                                        ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                        : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                    {{ $product->status_label }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @can('view', $product)
                                        <a href="{{ route('products.show', $product) }}"
                                            class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                            title="Lihat">
                                            <x-ui.icon name="eye" class="w-4 h-4" />
                                        </a>
                                    @endcan

                                    @can('update', $product)
                                        <button type="button" title="Edit"
                                            class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                            @click="$store.modal.open('product-form', @js([
                                                'id' => $product->id,
                                                'name' => $product->name,
                                                'category_id' => $product->category_id,
                                                'price' => $product->price,
                                                'stock' => $product->stock,
                                                'is_available' => (bool) $product->is_available,
                                                'image_url' => $product->image_url,
                                                'action' => route('products.update', $product),
                                            ]))">
                                            <x-ui.icon name="pencil" class="w-4 h-4" />
                                        </button>
                                    @endcan

                                    @can('delete', $product)
                                        <button type="button" title="Hapus"
                                            class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                            @click="$store.modal.open('confirm-delete', @js([
                                                'action' => route('products.destroy', $product),
                                                'method' => 'DELETE',
                                                'confirmText' => 'Ya, Hapus',
                                            ]))">
                                            <x-ui.icon name="trash" class="w-4 h-4" />
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>

    @include('products.partials.form-modal')

    <x-ui.confirm-modal
        id="confirm-delete"
        title="Hapus Produk"
        message="Produk yang dihapus tidak akan muncul lagi di katalog. Lanjutkan?"
    />

</x-layouts.app>