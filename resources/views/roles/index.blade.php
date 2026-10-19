@php
    $superAdminRole = \App\Models\User::SUPER_ADMIN_ROLE;
@endphp

<x-layouts.app title="Role & Permission">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white font-medium">Role & Permission</span>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                Role & Permission
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Atur role pengguna dan hak akses yang dimiliki masing-masing role.
            </p>
        </div>

        @can('create', \Spatie\Permission\Models\Role::class)
            <button
                type="button"
                @click="$store.modal.open('role-form', {})"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-xs transition-colors shrink-0"
            >
                <x-ui.icon name="plus" class="w-4 h-4" />
                Tambah Role
            </button>
        @endcan
    </div>

    {{-- Table Card --}}
    <div class="relative overflow-hidden bg-white shadow-xs dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">

        {{-- Table --}}
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4" scope="col">Nama Role</th>
                        <th class="py-3 px-4 text-center" scope="col">Total Permission</th>
                        <th class="py-3 px-4 text-right" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($roles as $role)
                        @php $isSuperAdmin = $role->name === $superAdminRole; @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $role->name }}</span>
                                    @if ($isSuperAdmin)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300">
                                            <x-ui.icon name="shield-check" class="w-3.5 h-3.5" />
                                            Terproteksi
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-3 px-4 text-center">
                                @if ($isSuperAdmin)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Semua akses</span>
                                @else
                                    <span class="inline-flex items-center justify-center min-w-6 h-6 px-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold text-xs">
                                        {{ $role->permissions_count }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    @can('update', $role)
                                        <button
                                            type="button"
                                            @click="$store.modal.open('role-form', {
                                                id: {{ $role->id }},
                                                name: @js($role->name),
                                                permissions: @js($role->permissions->pluck('name')->values())
                                            })"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                                            title="Edit Role"
                                        >
                                            <x-ui.icon name="pencil" class="w-4 h-4" />
                                        </button>
                                    @endcan

                                    @can('delete', $role)
                                        <button
                                            type="button"
                                            @click="$store.modal.open('confirm-delete-role', {
                                                action: @js(route('roles.destroy', $role)),
                                                method: 'DELETE',
                                                message: @js('Hapus role ' . $role->name . '? Role yang masih dipakai user tidak bisa dihapus.'),
                                                confirmText: 'Ya, Hapus'
                                            })"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-rose-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                                            title="Hapus Role"
                                        >
                                            <x-ui.icon name="trash" class="w-4 h-4" />
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-10 text-center text-gray-500 dark:text-gray-400">
                                Belum ada role. Klik "Tambah Role" untuk membuat yang pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $roles->links() }}
        </div>
    </div>

    {{-- Modals --}}
    @include('roles.partials.form-modal')

    <x-ui.confirm-modal
        id="confirm-delete-role"
        title="Hapus Role?"
        message="Role yang masih dipakai user tidak bisa dihapus. Tindakan ini tidak bisa dibatalkan."
        method="DELETE"
    />

</x-layouts.app>