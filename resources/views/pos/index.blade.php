<x-layouts.app title="Kasir">
    <div class="grid gap-4 lg:grid-cols-3">

        <section class="lg:col-span-2 space-y-4">
            <form method="GET" action="{{ route('pos.index') }}" class="flex gap-2">
                @if (request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari menu..."
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Cari
                </button>
            </form>

            <div class="flex flex-wrap gap-2">
                @php $activeCategory = request('category'); @endphp

                <a href="{{ route('pos.index', array_filter(['search' => request('search')])) }}"
                   class="rounded-full px-3 py-1 text-sm {{ !$activeCategory ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200' }}">
                    Semua
                </a>

                @foreach ($categories as $category)
                    <a href="{{ route('pos.index', array_filter(['category' => $category->id, 'search' => request('search')])) }}"
                       class="rounded-full px-3 py-1 text-sm {{ (string) $activeCategory === (string) $category->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            @if ($products->isEmpty())
                <div class="rounded-lg border border-dashed border-gray-300 p-10 text-center text-sm text-gray-500 dark:border-gray-600 dark:text-gray-400">
                    Tidak ada menu yang tersedia.
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <form method="POST" action="{{ route('pos.cart.store', $product) }}">
                            @csrf
                            <button
                                type="submit"
                                class="group flex h-full w-full flex-col overflow-hidden rounded-lg border border-gray-200 bg-white text-left transition hover:border-blue-500 hover:shadow dark:border-gray-700 dark:bg-gray-800"
                            >
                                <img
                                    src="{{ $product->image_url }}"
                                    alt="{{ $product->name }}"
                                    class="aspect-[4/3] w-full object-cover"
                                    loading="lazy"
                                >
                                <div class="flex flex-1 flex-col gap-1 p-3">
                                    <span class="line-clamp-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $product->name }}
                                    </span>
                                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                        {{ $product->formatted_price }}
                                    </span>
                                    <span class="mt-auto text-xs text-gray-500 dark:text-gray-400">
                                        Stok: {{ $product->stock }}
                                    </span>
                                </div>
                            </button>
                        </form>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="lg:sticky lg:top-4 lg:self-start">
            <div class="flex max-h-[calc(100vh-2rem)] flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

                <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white">
                        Keranjang
                        <span class="ml-1 text-sm font-normal text-gray-500">({{ $totalItems }} item)</span>
                    </h2>

                    @if ($lines->isNotEmpty())
                        <button
                            type="button"
                            @click="$store.modal.open('confirm-clear-cart', {
                                action: @js(route('pos.cart.clear')),
                                method: 'DELETE',
                                confirmText: 'Ya, Kosongkan'
                            })"
                            class="text-sm text-rose-600 hover:underline"
                        >
                            Kosongkan
                        </button>
                    @endif
                </div>

                @if ($errors->any())
                    <div class="m-4 mb-0 rounded-lg bg-rose-50 p-3 text-sm text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="flex-1 divide-y divide-gray-100 overflow-y-auto dark:divide-gray-700">
                    @forelse ($lines as $line)
                        @php $product = $line['product']; @endphp
                        <div class="space-y-2 p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product->formatted_price }}</p>
                                </div>

                                <form method="POST" action="{{ route('pos.cart.destroy', $product) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-rose-600 dark:text-gray-400 dark:hover:bg-gray-700"
                                            title="Hapus dari keranjang">
                                        <x-ui.icon name="trash" class="h-4 w-4" />
                                    </button>
                                </form>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="inline-flex items-center gap-1">
                                    <form method="POST" action="{{ route('pos.cart.update', $product) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="qty" value="{{ $line['qty'] - 1 }}">
                                        <button type="submit"
                                                class="h-7 w-7 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                            −
                                        </button>
                                    </form>

                                    <span class="w-8 text-center text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $line['qty'] }}
                                    </span>

                                    <form method="POST" action="{{ route('pos.cart.update', $product) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="qty" value="{{ $line['qty'] + 1 }}">
                                        <button type="submit"
                                                @disabled($line['qty'] >= $product->stock)
                                                class="h-7 w-7 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                            +
                                        </button>
                                    </form>
                                </div>

                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($line['subtotal'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            Keranjang masih kosong.<br>Klik menu di sebelah kiri untuk menambah.
                        </p>
                    @endforelse
                </div>

                <div class="space-y-3 border-t border-gray-200 p-4 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                        </span>
                    </div>

                    <button
                        type="button"
                        @click="$store.modal.open('pos-payment')"
                        @disabled($lines->isEmpty())
                        class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Bayar
                    </button>
                </div>
            </div>
        </aside>
    </div>

        <x-ui.confirm-modal
            id="confirm-clear-cart"
            title="Kosongkan Keranjang?"
            message="Semua item di keranjang akan dihapus."
            method="DELETE"
        />

        @include('pos.partials.payment-modal', ['total' => $totalAmount])
</x-layouts.app>