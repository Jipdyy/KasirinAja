<x-layouts.app title="Detail Produk">
    <div class="space-y-6">

        <a href="{{ route('products.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
            &larr; Kembali ke daftar produk
        </a>

        <div class="flex flex-col gap-5 rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800 sm:flex-row">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                 class="h-40 w-40 shrink-0 rounded-lg object-cover">

            <div class="flex-1 space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h1>
                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $product->status_label === 'Tersedia' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300' }}">
                        {{ $product->status_label }}
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm sm:max-w-md">
                    <dt class="text-gray-500 dark:text-gray-400">Kategori</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $product->category->name ?? '-' }}</dd>

                    <dt class="text-gray-500 dark:text-gray-400">Harga</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $product->formatted_price }}</dd>

                    <dt class="text-gray-500 dark:text-gray-400">Stok</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $product->stock }}</dd>

                    <dt class="text-gray-500 dark:text-gray-400">Dijual</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</dd>
                </dl>
            </div>
        </div>

        @if ($canSeeSales)
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Penjualan</h2>

                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total terjual</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($salesSummary->qty, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total omzet</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            Rp {{ number_format($salesSummary->total, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah transaksi</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($salesSummary->trx, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Invoice</th>
                                    <th class="px-4 py-3">Kasir</th>
                                    <th class="px-4 py-3 text-right">Qty</th>
                                    <th class="px-4 py-3 text-right">Harga</th>
                                    <th class="px-4 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($sales as $item)
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-3">
                                            {{ $item->transaction->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('transactions.show', $item->transaction) }}" class="hover:text-blue-600">
                                                {{ $item->transaction->invoice_code }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">{{ $item->transaction->cashier->name }}</td>
                                        <td class="px-4 py-3 text-right">{{ $item->qty }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                            Produk ini belum pernah terjual.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $sales->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>