<x-layouts.app title="Riwayat Transaksi">
    <div class="space-y-4">

        <div class="grid gap-3 sm:grid-cols-2">
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah transaksi</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($summary['count'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total omzet</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($summary['sum'], 0, ',', '.') }}
                </p>
            </div>
        </div>

        <form method="GET" action="{{ route('transactions.index') }}"
              class="grid gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 sm:grid-cols-2 lg:grid-cols-6">

            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Invoice</label>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="INV-..."
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            @if ($canViewAll)
                <div>
                    <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Kasir</label>
                    <input type="search" name="cashier" value="{{ $filters['cashier'] ?? '' }}" placeholder="Nama kasir"
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            @endif

            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Dari tanggal</label>
                <input type="date" name="start" value="{{ $filters['start'] ?? '' }}"
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Sampai tanggal</label>
                <input type="date" name="end" value="{{ $filters['end'] ?? '' }}"
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('end')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Metode</label>
                <select name="payment"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua</option>
                    <option value="tunai" @selected(($filters['payment'] ?? '') === 'tunai')>Tunai</option>
                    <option value="qris" @selected(($filters['payment'] ?? '') === 'qris')>QRIS</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Terapkan
                </button>
                <a href="{{ route('transactions.index') }}" class="text-sm text-gray-500 hover:underline dark:text-gray-400">
                    Reset
                </a>
            </div>
        </form>

        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
            <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Invoice</th>
                        <th class="px-4 py-3">Waktu</th>
                        @if ($canViewAll)
                            <th class="px-4 py-3">Kasir</th>
                        @endif
                        <th class="px-4 py-3 text-right">Item</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3">Metode</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $transaction->invoice_code }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                {{ $transaction->created_at->format('d M Y, H:i') }}
                            </td>
                            @if ($canViewAll)
                                <td class="px-4 py-3">{{ $transaction->cashier->name }}</td>
                            @endif
                            <td class="px-4 py-3 text-right">{{ $transaction->total_items }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                {{ $transaction->formatted_total_amount }}
                            </td>
                            <td class="px-4 py-3">{{ $transaction->payment_method_label }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('transactions.show', $transaction) }}"
                                   class="inline-flex rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-gray-700"
                                   title="Lihat Detail">
                                    <x-ui.icon name="eye" class="h-4 w-4" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canViewAll ? 7 : 6 }}" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $transactions->links() }}
    </div>
</x-layouts.app>