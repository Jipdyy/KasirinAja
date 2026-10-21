<x-layouts.app title="Dashboard">
    <div class="space-y-4">

        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $canViewAll ? 'Ringkasan penjualan hari ini' : 'Ringkasan transaksi Anda hari ini' }}
                &middot; {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Pendapatan hari ini</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($stats['revenue'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah transaksi</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($stats['transactions'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Menu terjual</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($stats['items'], 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 lg:col-span-2">
                <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white">Transaksi terbaru</h2>
                    @can('viewAny', App\Models\Transaction::class)
                        <a href="{{ route('transactions.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
                    @endcan
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Invoice</th>
                                <th class="px-4 py-3">Jam</th>
                                <th class="px-4 py-3">Kasir</th>
                                <th class="px-4 py-3">Metode</th>
                                <th class="px-4 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($recent as $transaction)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                        @can('view', $transaction)
                                            <a href="{{ route('transactions.show', $transaction) }}" class="hover:text-blue-600">
                                                {{ $transaction->invoice_code }}
                                            </a>
                                        @else
                                            {{ $transaction->invoice_code }}
                                        @endcan
                                    </td>
                                    <td class="px-4 py-3">{{ $transaction->created_at->format('H:i') }}</td>
                                    <td class="px-4 py-3">{{ $transaction->cashier->name }}</td>
                                    <td class="px-4 py-3">{{ $transaction->payment_method_label }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                        {{ $transaction->formatted_total_amount }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada transaksi hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-4">

                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-3 font-semibold text-gray-900 dark:text-white">Menu terlaris</h2>

                    <ol class="space-y-3">
                        @forelse ($topProducts as $i => $item)
                            <li class="flex items-center justify-between gap-2 text-sm">
                                <span class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 text-xs font-medium dark:bg-gray-700">
                                        {{ $i + 1 }}
                                    </span>
                                    {{ $item->name }}
                                </span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item->qty }}x</span>
                            </li>
                        @empty
                            <li class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada data.</li>
                        @endforelse
                    </ol>
                </div>

                @can('products.view')
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="font-semibold text-gray-900 dark:text-white">Peringatan stok</h2>
                        <p class="mb-3 text-xs text-gray-500 dark:text-gray-400">Menu aktif dengan stok di bawah {{ $threshold }}</p>

                        <ul class="space-y-3">
                            @forelse ($lowStock as $product)
                                <li class="flex items-center justify-between gap-2 text-sm">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $product->name }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $product->stock === 0 ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' }}">
                                        {{ $product->stock === 0 ? 'Habis' : 'Sisa ' . $product->stock }}
                                    </span>
                                </li>
                            @empty
                                <li class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">Stok semua menu aman.</li>
                            @endforelse
                        </ul>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-layouts.app>