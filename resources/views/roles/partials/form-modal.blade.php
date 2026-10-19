<x-ui.modal id="role-form">
    <x-slot:title>
        <span x-text="$store.modal.payload?.id ? 'Edit Role' : 'Tambah Role'"></span>
    </x-slot:title>

    <div
        x-data="{
            id: null,
            name: '',
            permissions: [],

            fill(p) {
                this.id = p?.id ?? null;
                this.name = p?.name ?? '';
                this.permissions = p?.permissions ?? [];
            },

            selectedIn(names) {
                return names.filter(n => this.permissions.includes(n)).length;
            },

            isAll(names) {
                return names.length > 0 && this.selectedIn(names) === names.length;
            },

            isPartial(names) {
                const n = this.selectedIn(names);
                return n > 0 && n < names.length;
            },

            toggleGroup(names) {
                const rest = this.permissions.filter(p => !names.includes(p));
                this.permissions = this.isAll(names) ? rest : [...rest, ...names];
            },
        }"
        x-init="
            $watch('$store.modal.payload', p => fill(p));

            @if ($errors->any())
                $store.modal.open('role-form', {
                    id: @js(old('_id')),
                    name: @js(old('name')),
                    permissions: @js(old('permissions', [])),
                });
            @endif
        "
    >
        <form method="POST"
              :action="id ? '{{ url('/roles') }}/' + id : '{{ route('roles.store') }}'"
              class="space-y-4">
            @csrf

            <template x-if="id">
                <div>
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_id" :value="id">
                </div>
            </template>

            {{-- Nama Role --}}
            <div>
                <label for="role-name" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Nama Role</label>
                <input type="text" id="role-name" name="name" x-model="name" maxlength="255" required
                    placeholder="Contoh: Kasir, Supervisor..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            {{-- Permission --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Hak Akses (Permission)</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        <span x-text="permissions.length"></span> dipilih
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse ($permissions as $resource => $items)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700">
                            {{-- Header grup + pilih semua --}}
                            <label class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-700/50 rounded-t-lg cursor-pointer">
                                <input type="checkbox"
                                    :checked="isAll(@js($items->pluck('name')->values()))"
                                    x-effect="$el.indeterminate = isPartial(@js($items->pluck('name')->values()))"
                                    @change="toggleGroup(@js($items->pluck('name')->values()))"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ \Illuminate\Support\Str::headline($resource) }}
                                </span>
                            </label>

                            {{-- Daftar aksi --}}
                            <div class="grid grid-cols-2 gap-2 p-3">
                                @foreach ($items as $permission)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            x-model="permissions"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm dark:bg-gray-700 dark:border-gray-600">
                                        {{ \Illuminate\Support\Str::headline(\Illuminate\Support\Str::after($permission->name, '.')) }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Belum ada permission di database. Jalankan PermissionSeeder terlebih dahulu.
                        </p>
                    @endforelse
                </div>

                @error('permissions') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
                @error('permissions.*') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <x-ui.button type="button" variant="secondary" @click="$store.modal.close()">Batal</x-ui.button>
                <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
            </div>
        </form>
    </div>
</x-ui.modal>