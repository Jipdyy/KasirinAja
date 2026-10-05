{{-- Overlay gelap di belakang sidebar, HANYA muncul saat sidebar terbuka
     di layar kecil. Klik di luar sidebar akan menutupnya. --}}
<div
    x-show="$store.sidebar.open"
    x-transition.opacity
    @click="$store.sidebar.open = false"
    class="fixed inset-0 z-20 bg-black/50 lg:hidden"
    style="display: none;"
></div>

<aside
    id="main-sidebar"
    :class="$store.sidebar.open ? 'translate-x-0' : '-translate-x-full'"
    class="fixed top-16 left-0 z-30 w-64 h-[calc(100vh-4rem)]
           bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700
           overflow-y-auto transition-transform duration-200
           lg:translate-x-0"
>
    <nav class="p-3 space-y-1">

        <x-layout.sidebar-item :href="route('dashboard')" label="Dashboard" icon="dashboard" />

        @can('pos.access')
            <x-layout.sidebar-item :href="route('pos.index')" label="Transaksi Kasir POS" icon="pos" />
        @endcan

        @canany(['categories.view', 'products.view'])
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Katalog
            </p>
            @can('categories.view')
                <x-layout.sidebar-item :href="route('categories.index')" label="Kategori Produk" icon="tag" />
            @endcan
            @can('products.view')
                <x-layout.sidebar-item :href="route('products.index')" label="Produk" icon="box" />
            @endcan
        @endcanany

        @canany(['users.view', 'roles.view'])
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Pengaturan Hak Akses
            </p>
            @can('users.view')
                <x-layout.sidebar-item :href="route('users.index')" label="Manajemen User" icon="users" />
            @endcan
            @can('roles.view')
                <x-layout.sidebar-item :href="route('roles.index')" label="Role & Permission" icon="shield-check" />
            @endcan
        @endcanany

        @canany(['transactions.view-own', 'transactions.view-all', 'reports.view'])
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Laporan & Rekap
            </p>
            @canany(['transactions.view-own', 'transactions.view-all'])
                <x-layout.sidebar-item :href="route('transactions.index')" label="Riwayat Transaksi" icon="clock" />
            @endcanany
            @can('reports.view')
                <x-layout.sidebar-item :href="route('reports.index')" label="Laporan Penjualan" icon="chart-bar" />
            @endcan
        @endcanany

    </nav>
</aside>