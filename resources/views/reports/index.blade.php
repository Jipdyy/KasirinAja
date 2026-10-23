<x-layouts.app title="Laporan Penjualan">
    <div class="space-y-4">

        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Laporan Penjualan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ \Carbon\Carbon::parse($start)->translatedFormat('d M Y') }}
                &ndash;
                {{ \Carbon\Carbon::parse($end)->translatedFormat('d M Y') }}
            </p>
        </div>

        <form method="GET" action="{{ route('reports.sales') }}"
              class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">

            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Dari tanggal</label>
                <input type="date" name="start" value="{{ $start }}"
                       class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Sampai tanggal</label>
                <input type="date" name="end" value="{{ $end }}"
                       class="rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('end')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Terapkan
            </button>

            <div class="flex flex-wrap gap-2 text-sm sm:ml-auto">
                <a href="{{ route('reports.sales', ['start' => now()->toDateString(), 'end' => now()->toDateString()]) }}"
                   class="rounded-full bg-gray-100 px-3 py-1 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200">Hari ini</a>
                <a href="{{ route('reports.sales', ['start' => now()->subDays(6)->toDateString(), 'end' => now()->toDateString()]) }}"
                   class="rounded-full bg-gray-100 px-3 py-1 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200">7 hari</a>
                <a href="{{ route('reports.sales', ['start' => now()->startOfMonth()->toDateString(), 'end' => now()->toDateString()]) }}"
                   class="rounded-full bg-gray-100 px-3 py-1 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200">Bulan ini</a>
            </div>
        </form>

        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total omzet</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($summary['revenue'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total transaksi</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($summary['transactions'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ number_format($summary['items'], 0, ',', '.') }} menu terjual
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Rata-rata per transaksi</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($summary['average'], 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Rasio non-tunai</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($paymentRatio['noncash_percent'], 1, ',', '.') }}%
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">dari total omzet</p>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-4 font-semibold text-gray-900 dark:text-white">Tren penjualan harian</h2>

            @php
                $days = count($dailyTrend);
                $max = max(1, $days ? max($dailyTrend) : 0);
                $step = max(1, (int) ceil($days / 15));
            @endphp

            @if ($summary['transactions'] === 0)
                <p class="py-16 text-center text-sm text-gray-500 dark:text-gray-400">
                    Tidak ada penjualan pada rentang ini.
                </p>
            @else
                <div class="flex h-48 items-end gap-px">
                    @foreach ($dailyTrend as $day => $total)
                        <div class="flex h-full flex-1 items-end"
                             title="{{ \Carbon\Carbon::parse($day)->translatedFormat('d M Y') }} &mdash; Rp {{ number_format($total, 0, ',', '.') }}">
                            <div class="w-full rounded-t bg-blue-500 transition-all hover:bg-blue-600"
                                 style="height: {{ $total > 0 ? max(2, round($total / $max * 100)) : 0 }}%"></div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-1 flex gap-px">
                    @foreach ($dailyTrend as $day => $total)
                        <span class="flex-1 overflow-visible whitespace-nowrap text-center text-[10px] text-gray-500 dark:text-gray-400">
                            {{ $loop->index % $step === 0 ? \Carbon\Carbon::parse($day)->format($days > 31 ? 'd/m' : 'd') : '' }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="grid gap-4 lg:grid-cols-2">

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-3 font-semibold text-gray-900 dark:text-white">Komposisi omzet per kategori</h2>

                <ul class="space-y-3">
                    @forelse ($categoryComposition as $category)
                        <li>
                            <div class="mb-1 flex items-center justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-300">{{ $category['name'] }}</span>
                                <span class="text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($category['total'], 0, ',', '.') }}
                                    &middot; {{ number_format($category['percent'], 1, ',', '.') }}%
                                </span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                                <div class="h-full rounded-full bg-blue-500" style="width: {{ min(100, $category['percent']) }}%"></div>
                            </div>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-3 font-semibold text-gray-900 dark:text-white">Menu terlaris</h2>

                <ol class="space-y-3">
                    @forelse ($topProducts as $i => $item)
                        <li class="flex items-center justify-between gap-2 text-sm">
                            <span class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-medium dark:bg-gray-700">
                                    {{ $i + 1 }}
                                </span>
                                {{ $item->name }}
                            </span>
                            <span class="text-right">
                                <span class="block font-semibold text-gray-900 dark:text-white">{{ number_format($item->qty, 0, ',', '.') }}x</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                            </span>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada data.</li>
                    @endforelse
                </ol>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 p-4 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white">Rincian per tanggal</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3 text-right">Transaksi</th>
                            <th class="px-4 py-3 text-right">Menu terjual</th>
                            <th class="px-4 py-3 text-right">Tunai</th>
                            <th class="px-4 py-3 text-right">QRIS</th>
                            <th class="px-4 py-3 text-right">Omzet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($dailyBreakdown as $row)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($row['date'])->translatedFormat('D, d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-right">{{ number_format($row['transactions'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($row['items'], 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">Rp {{ number_format($row['cash'], 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">Rp {{ number_format($row['non_cash'], 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($row['revenue'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada transaksi pada rentang ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($dailyBreakdown->isNotEmpty())
                        <tfoot class="bg-gray-50 font-semibold text-gray-900 dark:bg-gray-700 dark:text-white">
                            <tr>
                                <td class="px-4 py-3">Total</td>
                                <td class="px-4 py-3 text-right">{{ number_format($dailyBreakdown->sum('trx'), 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($dailyBreakdown->sum('items'), 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">Rp {{ number_format($dailyBreakdown->sum('cash'), 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">Rp {{ number_format($dailyBreakdown->sum('qris'), 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">Rp {{ number_format($dailyBreakdown->sum('total'), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>