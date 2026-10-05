<x-layouts.app title="Kategori Produk">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-600">Dashboard</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white font-medium">Kategori Produk</span>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                Kategori Produk
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola kelompok menu makanan, minuman, dan snack kafetaria.
            </p>
        </div>

        <button
            type="button"
            @click="$store.modal.open('category-form', {})"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-xs transition-colors shrink-0"
        >
            <x-ui.icon name="plus" class="w-4 h-4" />
            Tambah Kategori
        </button>
    </div>

    {{-- Table Card --}}
    <div class="relative overflow-hidden bg-white shadow-xs dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">

        {{-- Toolbar --}}
        <form method="GET" class="p-4 flex flex-col md:flex-row items-stretch md:items-center gap-3 border-b border-gray-200 dark:border-gray-700">
            <div class="relative flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <x-ui.icon name="search" class="w-4 h-4" />
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama kategori..."
                    class="pl-9 text-sm bg-gray-50 border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white block w-full p-2.5 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <button type="submit" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                Cari
            </button>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4" scope="col">Nama Kategori</th>
                        <th class="py-3 px-4" scope="col">Deskripsi</th>
                        <th class="py-3 px-4 text-center" scope="col">Total Produk</th>
                        <th class="py-3 px-4 text-right" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">
                                {{ $category->name }}
                            </td>
                            <td class="py-3 px-4 text-gray-500 dark:text-gray-400">
                                {{ $category->description ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-6 h-6 px-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold text-xs">
                                    {{ $category->products_count }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a
                                        href="{{ route('categories.show', $category) }}"
                                        class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                                        title="Lihat Detail"
                                    >
                                        <x-ui.icon name="eye" class="w-4 h-4" />
                                    </a>

                                    <button
                                        type="button"
                                        @click="$store.modal.open('category-form', {
                                            id: {{ $category->id }},
                                            name: @js($category->name),
                                            description: @js($category->description)
                                        })"
                                        class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                                        title="Edit Kategori"
                                    >
                                        <x-ui.icon name="pencil" class="w-4 h-4" />
                                    </button>

                                    <button
                                        type="button"
                                        @click="$store.modal.open('confirm-delete-category', {
                                            action: @js(route('categories.destroy', $category)),
                                            method: 'DELETE',
                                            confirmText: 'Ya, Hapus'
                                        })"
                                        class="p-1.5 rounded-lg text-gray-500 hover:text-rose-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                                        title="Hapus Kategori"
                                    >
                                        <x-ui.icon name="trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-500 dark:text-gray-400">
                                Belum ada kategori. Klik "Tambah Kategori" untuk membuat yang pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $categories->links() }}
        </div>
    </div>

    {{-- Modals --}}
    @include('categories.partials.form-modal')

    <x-ui.confirm-modal
        id="confirm-delete-category"
        title="Hapus Kategori?"
        message="Kategori yang masih punya produk tidak bisa dihapus. Tindakan ini tidak bisa dibatalkan."
        method="DELETE"
    />

</x-layouts.app>