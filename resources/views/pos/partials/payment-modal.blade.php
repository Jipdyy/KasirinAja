<div
    x-data="{
        method: 'tunai',
        cash: '',
        total: @js($total),
        get change() { return Math.max(0, (parseInt(this.cash) || 0) - this.total); },
        get enough() { return this.method === 'qris' || (parseInt(this.cash) || 0) >= this.total; },
        rp(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(n); },
    }"
>
    <x-ui.modal id="pos-payment" title="Pembayaran">
        <form id="pos-checkout-form" method="POST" action="{{ route('pos.checkout') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="payment_method" :value="method">
            <input type="hidden" name="cash_amount" :value="method === 'tunai' ? cash : ''">

            <div class="rounded-lg bg-gray-50 p-3 text-center dark:bg-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total tagihan</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="rp(total)"></p>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="method = 'tunai'"
                        :class="method === 'tunai' ? 'border-blue-600 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'border-gray-300 text-gray-700 dark:border-gray-600 dark:text-gray-200'"
                        class="rounded-lg border px-3 py-2 text-sm font-medium">Tunai</button>
                <button type="button" @click="method = 'qris'"
                        :class="method === 'qris' ? 'border-blue-600 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'border-gray-300 text-gray-700 dark:border-gray-600 dark:text-gray-200'"
                        class="rounded-lg border px-3 py-2 text-sm font-medium">QRIS</button>
            </div>

            <div x-show="method === 'tunai'" class="space-y-3">
                <div>
                    <label class="mb-1 block text-sm text-gray-700 dark:text-gray-300">Uang diterima</label>
                    <input type="number" min="0" x-model="cash" inputmode="numeric"
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="cash = total"
                            class="rounded-lg border border-gray-300 px-3 py-1 text-xs text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        Uang pas
                    </button>
                    @foreach ([20000, 50000, 100000] as $nominal)
                        <button type="button" @click="cash = {{ $nominal }}"
                                class="rounded-lg border border-gray-300 px-3 py-1 text-xs text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                            {{ number_format($nominal, 0, ',', '.') }}
                        </button>
                    @endforeach
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Kembalian</span>
                    <span class="font-semibold"
                          :class="enough ? 'text-emerald-600' : 'text-rose-600'"
                          x-text="enough ? rp(change) : 'Uang kurang'"></span>
                </div>
            </div>

            <p x-show="method === 'qris'" class="text-sm text-gray-500 dark:text-gray-400">
                Pastikan pembayaran QRIS sudah masuk sebelum menekan tombol konfirmasi.
            </p>
        </form>

        <x-slot:footer>
            <button type="button" @click="$store.modal.close()"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                Batal
            </button>
            <button type="submit" form="pos-checkout-form" :disabled="!enough"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                Konfirmasi
            </button>
        </x-slot:footer>
    </x-ui.modal>
</div>