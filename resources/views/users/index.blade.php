<x-layouts.app title="Manajemen User">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white font-medium">Manajemen User</span>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Manajemen User</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola akun administrator dan kasir.</p>
        </div>

        @can('users.create')
            <button type="button"
                @click="$store.modal.open('user-form', {})"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-xs transition-colors shrink-0">
                <x-ui.icon name="plus" class="w-4 h-4" />
                Tambah User
            </button>
        @endcan
    </div>

    <div class="relative overflow-hidden bg-white shadow-xs dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">

        {{-- Toolbar --}}
        <form method="GET" class="p-4 flex flex-col md:flex-row items-stretch md:items-center gap-3 border-b border-gray-200 dark:border-gray-700">
            <div class="relative flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <x-ui.icon name="search" class="w-4 h-4" />
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="pl-9 text-sm bg-gray-50 border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white block w-full p-2.5">
            </div>

            <select name="role"
                class="text-sm bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white p-2.5">
                <option value="">Semua Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
                @endforeach
            </select>

            <select name="status"
                class="text-sm bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white p-2.5">
                <option value="">Semua Status</option>
                <option value="Aktif" @selected(request('status') === 'Aktif')>Aktif</option>
                <option value="Nonaktif" @selected(request('status') === 'Nonaktif')>Nonaktif</option>
            </select>

            <button type="submit"
                class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                Cari
            </button>
        </form>

        {{-- Tabel --}}
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4" scope="col">Nama</th>
                        <th class="py-3 px-4" scope="col">Email</th>
                        <th class="py-3 px-4" scope="col">Role</th>
                        <th class="py-3 px-4" scope="col">Status</th>
                        <th class="py-3 px-4" scope="col">Login Terakhir</th>
                        <th class="py-3 px-4 text-right" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="py-3 px-4">
                                @forelse ($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-400">-</span>
                                @endforelse
                            </td>
                            <td class="py-3 px-4">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-500 dark:text-gray-400">
                                @if ($user->last_login_at)
                                    <span title="{{ $user->last_login_at->format('d/m/Y H:i') }}">
                                        {{ $user->last_login_at->diffForHumans() }}
                                    </span>
                                @else
                                    Belum pernah login
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    @can('users.view')
                                        <a href="{{ route('users.show', $user) }}" title="Lihat Detail"
                                           class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                                            <x-ui.icon name="eye" class="w-4 h-4" />
                                        </a>
                                    @endcan

                                    @can('users.edit')
                                        <button type="button" title="Edit User"
                                            @click="$store.modal.open('user-form', {
                                                id: {{ $user->id }},
                                                name: @js($user->name),
                                                email: @js($user->email),
                                                role: @js($user->roles->first()?->name),
                                                is_active: {{ (int) $user->is_active }}
                                            })"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                                            <x-ui.icon name="pencil" class="w-4 h-4" />
                                        </button>
                                    @endcan

                                    @can('users.reset-password')
                                        <button type="button" title="Reset Password"
                                            @click="$store.modal.open('user-reset-password', {
                                                id: {{ $user->id }},
                                                name: @js($user->name)
                                            })"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                                            <x-ui.icon name="key" class="w-4 h-4" />
                                        </button>
                                    @endcan

                                    @can('users.deactivate')
                                        @if (auth()->id() !== $user->id)
                                            <button type="button"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan Kembali' }}"
                                                @click="$store.modal.open('confirm-toggle-user', {
                                                    action: @js(route('users.status', $user)),
                                                    method: 'PATCH',
                                                    tone: @js($user->is_active ? 'danger' : 'success'),
                                                    message: @js($user->is_active
                                                        ? 'Akun nonaktif tidak bisa login. Riwayat transaksinya tetap tersimpan.'
                                                        : 'Akun ini akan bisa login kembali.'),
                                                    confirmText: @js($user->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan')
                                                })"
                                                class="p-1.5 rounded-lg {{ $user->is_active
                                                    ? 'text-gray-500 hover:text-rose-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700'
                                                    : 'text-rose-600 bg-rose-50 hover:bg-rose-100 dark:text-rose-400 dark:bg-rose-900/30 dark:hover:bg-rose-900/50' }}">
                                                <x-ui.icon name="power" class="w-4 h-4" />
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada user yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $users->links() }}
        </div>
    </div>

    @include('users.partials.form-modal')
    @include('users.partials.reset-password-modal')

    <x-ui.confirm-modal
        id="confirm-toggle-user"
        title="Ubah Status User?"
        message="Akun nonaktif tidak bisa login. Riwayat transaksinya tetap tersimpan."
        method="PATCH"
    />

</x-layouts.app>