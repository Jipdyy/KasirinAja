x  <x-layouts.app :title="$product->name">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <span>/</span>
                <a href="{{ route('products.index') }}" class="hover:text-blue-600">Produk</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ $product->name }}</span>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ $product->name }}
            </h1>
        </div>

        <a href="{{ route('products.index') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 hover:bg-gray-100 text-sm font-medium dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 shrink-0">
            &larr; Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Gambar --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            @if ($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                     class="w-full aspect-square object-cover rounded-lg border border-gray-200 dark:border-gray-600">
            @else
                <div class="w-full aspect-square flex flex-col items-center justify-center gap-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400">
                    <x-ui.icon name="box" class="w-12 h-12" />
                    <span class="text-sm">Belum ada gambar</span>
                </div>
            @endif
        </div>

        {{-- Informasi --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 text-sm">

                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Nama Produk</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $product->name }}</dd>
                </div>

                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Kategori</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                        {{ $product->category?->name ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Harga</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $product->formatted_price }}</dd>
                </div>

                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Stok Saat Ini</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $product->stock }}</dd>
                </div>

                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1">
                        @if ($product->isSellable())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                {{ $product->status_label }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">
                                {{ $product->status_label }}
                            </span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Tampil di Kasir</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                        {{ $product->is_active ? 'Ya' : 'Tidak' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Dibuat</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                        {{ $product->created_at->format('d/m/Y H:i') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                        {{ $product->updated_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
            </dl>

            @unless ($product->isSellable())
                <p class="mt-6 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-sm text-gray-600 dark:text-gray-300">
                    @if (! $product->is_active)
                        Produk ini disembunyikan dari kasir, jadi tidak bisa dijual walaupun stok masih ada.
                    @else
                        Stok produk ini 0, jadi otomatis berstatus Habis.
                    @endif
                </p>
            @endunless
        </div>
    </div>
</x-layouts.app>