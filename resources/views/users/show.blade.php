<x-layouts.app :title="$user->name">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <span>/</span>
                <a href="{{ route('users.index') }}" class="hover:text-blue-600">Manajemen User</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ $user->name }}</span>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
        </div>

        <a href="{{ route('users.index') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 hover:bg-gray-100 text-sm font-medium dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 shrink-0">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 text-sm">

            <div>
                <dt class="text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $user->name }}</dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $user->email }}</dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">Role</dt>
                <dd class="mt-1">
                    @forelse ($user->roles as $role)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $role->name }}
                        </span>
                    @empty
                        <span class="text-gray-400">-</span>
                    @endforelse
                </dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">Status Akun</dt>
                <dd class="mt-1">
                    @if ($user->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">Nonaktif</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">Dibuat</dt>
                <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">Login Terakhir</dt>
                <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                    {{ $user->last_login_at?->format('d/m/Y H:i') ?? 'Belum pernah login' }}
                </dd>
            </div>

            @isset($totalTransactions)
                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Total Transaksi yang Ditangani</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $totalTransactions }}</dd>
                </div>
            @endisset
        </dl>
    </div>

</x-layouts.app>