<x-layouts.app title="Detail Transaksi">
    <style media="print">
        nav, aside, header { display: none !important; }
        main { padding: 0 !important; }
    </style>

    <div class="mx-auto max-w-2xl space-y-4">

        <div class="flex items-center justify-between print:hidden">
            <a href="{{ route('transactions.index') }}" class="text-sm text-blue-600 hover:underline">
                &larr; Kembali
            </a>
            <button type="button" onclick="window.print()"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Cetak Struk
            </button>
        </div>

        <div class="space-y-5 rounded-lg border border-gray-200 bg-white p-6 text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 print:border-0 print:p-0 print:text-black">

            <div class="border-b border-dashed border-gray-300 pb-4 text-center dark:border-gray-600">
                <h1 class="text-lg font-bold">{{ config('app.name') }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">Struk Pembayaran</p>
            </div>

            <dl class="grid grid-cols-2 gap-y-1 text-sm">
                <dt class="text-gray-500 dark:text-gray-400">Invoice</dt>
                <dd class="text-right font-medium">{{ $transaction->invoice_code }}</dd>

                <dt class="text-gray-500 dark:text-gray-400">Tanggal</dt>
                <dd class="text-right">{{ $transaction->created_at->format('d M Y, H:i') }}</dd>

                <dt class="text-gray-500 dark:text-gray-400">Kasir</dt>
                <dd class="text-right">{{ $transaction->cashier->name }}</dd>

                <dt class="text-gray-500 dark:text-gray-400">Metode</dt>
                <dd class="text-right">{{ $transaction->payment_method_label }}</dd>
            </dl>

            <div class="border-y border-dashed border-gray-300 py-4 dark:border-gray-600">
                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="pb-2 text-left font-medium">Menu</th>
                            <th class="pb-2 text-right font-medium">Qty</th>
                            <th class="pb-2 text-right font-medium">Harga</th>
                            <th class="pb-2 text-right font-medium">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->items as $item)
                            <tr>
                                <td class="py-1 pr-2">{{ $item->product_name }}</td>
                                <td class="py-1 text-right">{{ $item->qty }}</td>
                                <td class="py-1 text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-1 text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <dl class="grid grid-cols-2 gap-y-1 text-sm">
                <dt class="text-gray-500 dark:text-gray-400">Total item</dt>
                <dd class="text-right">{{ $transaction->total_items }}</dd>

                <dt class="font-semibold">Total</dt>
                <dd class="text-right text-base font-bold">{{ $transaction->formatted_total_amount }}</dd>

                @if ($transaction->payment_method === \App\Models\Transaction::PAYMENT_TUNAI)
                    <dt class="text-gray-500 dark:text-gray-400">Tunai</dt>
                    <dd class="text-right">Rp {{ number_format($transaction->cash_amount, 0, ',', '.') }}</dd>

                    <dt class="text-gray-500 dark:text-gray-400">Kembalian</dt>
                    <dd class="text-right">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</dd>
                @endif
            </dl>

            <p class="border-t border-dashed border-gray-300 pt-4 text-center text-xs text-gray-500 dark:border-gray-600 dark:text-gray-400">
                Terima kasih atas kunjungan Anda
            </p>
        </div>
    </div>
</x-layouts.app>