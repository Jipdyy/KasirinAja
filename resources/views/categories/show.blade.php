<x-layouts.app :title="$category->name">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <span>/</span>
                <a href="{{ route('categories.index') }}" class="hover:text-blue-600">Kategori Produk</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ $category->name }}</span>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ $category->name }}
            </h1>
        </div>

        <a href="{{ route('categories.index') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 hover:bg-gray-100 text-sm font-medium dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 shrink-0">
            &larr; Kembali
        </a>
    </div>

    {{-- Informasi kategori --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-5 text-sm">
            <div>
                <dt class="text-gray-500 dark:text-gray-400">Nama Kategori</dt>
                <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $category->name }}</dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">Total Produk</dt>
                <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $products->total() }}</dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">Dibuat</dt>
                <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                    {{ $category->created_at->format('d/m/Y H:i') }}
                </dd>
            </div>

            <div class="sm:col-span-3">
                <dt class="text-gray-500 dark:text-gray-400">Deskripsi</dt>
                <dd class="mt-1 text-gray-900 dark:text-white">{{ $category->description ?: '-' }}</dd>
            </div>
        </dl>
    </div>

    {{-- Daftar produk dalam kategori --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Produk dalam kategori ini</h2>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4" scope="col">Gambar</th>
                        <th class="py-3 px-4" scope="col">Nama</th>
                        <th class="py-3 px-4" scope="col">Harga</th>
                        <th class="py-3 px-4 text-center" scope="col">Stok</th>
                        <th class="py-3 px-4" scope="col">Status</th>
                        <th class="py-3 px-4 text-right" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                     class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">
                                {{ $product->name }}
                            </td>
                            <td class="py-3 px-4 text-gray-700 dark:text-gray-300">
                                {{ $product->formatted_price }}
                            </td>
                            <td class="py-3 px-4 text-center text-gray-700 dark:text-gray-300">
                                {{ $product->stock }}
                            </td>
                            <td class="py-3 px-4">
                                @if ($product->isSellable())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        {{ $product->status_label }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">
                                        {{ $product->status_label }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                @can('products.view')
                                    <a href="{{ route('products.show', $product) }}"
                                       class="inline-flex p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                                       title="Lihat Detail">
                                        <x-ui.icon name="eye" class="w-4 h-4" />
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-500 dark:text-gray-400">
                                Belum ada produk di kategori ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $products->links() }}
        </div>
    </div>

</x-layouts.app>