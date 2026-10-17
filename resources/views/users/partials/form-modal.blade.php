<x-ui.modal id="user-form">
    <x-slot:title>
        <span x-text="$store.modal.payload?.id ? 'Edit User' : 'Tambah User'"></span>
    </x-slot:title>

    <div
        x-data="{
            id: null,
            name: '',
            email: '',
            role: '',
            is_active: '1',

            fill(p) {
                this.id = p?.id ?? null;
                this.name = p?.name ?? '';
                this.email = p?.email ?? '';
                this.role = p?.role ?? '';
                this.is_active = String(p?.is_active ?? 1);
            },
        }"
        x-init="
            $watch('$store.modal.payload', p => fill(p));

            @if ($errors->any())
                $store.modal.open('user-form', {
                    id: @js(old('_id')),
                    name: @js(old('name')),
                    email: @js(old('email')),
                    role: @js(old('role')),
                    is_active: @js(old('is_active', 1)),
                });
            @endif
        "
    >
        <form method="POST"
              :action="id ? '{{ url('/users') }}/' + id : '{{ route('users.store') }}'"
              class="space-y-4">
            @csrf

            <template x-if="id">
                <div>
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_id" :value="id">
                </div>
            </template>

            {{-- Nama --}}
            <div>
                <label for="user-name" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap</label>
                <input type="text" id="user-name" name="name" x-model="name" maxlength="255" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="user-email" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="user-email" name="email" x-model="email" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            {{-- Role --}}
            <div>
                <label for="user-role" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                <select id="user-role" name="role" x-model="role" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <template x-if="!id">
                <div class="space-y-4">
                    <div>
                        <label for="user-password" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" id="user-password" name="password" minlength="8" required autocomplete="new-password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="user-password-confirmation" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password</label>
                        <input type="password" id="user-password-confirmation" name="password_confirmation" required autocomplete="new-password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
            </template>

            {{-- Status --}}
            <div>
                <label for="user-status" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Status Akun</label>
                <select id="user-status" name="is_active" x-model="is_active"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
                @error('is_active') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <x-ui.button type="button" variant="secondary" @click="$store.modal.close()">Batal</x-ui.button>
                <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
            </div>
        </form>
    </div>
</x-ui.modal>