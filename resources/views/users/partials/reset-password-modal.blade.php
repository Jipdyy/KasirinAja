<x-ui.modal id="user-reset-password">
    <x-slot:title>Reset Password</x-slot:title>

    <div
        x-data="{ id: null, name: '' }"
        x-init="
            $watch('$store.modal.payload', p => { id = p?.id ?? null; name = p?.name ?? ''; });

            @if ($errors->resetPassword->any())
                $store.modal.open('user-reset-password', { id: @js(old('_id')), name: @js(old('_name')) });
            @endif
        "
    >
        <p class="mb-4 text-sm text-gray-600 dark:text-gray-300">
            Atur password baru untuk <span class="font-semibold" x-text="name"></span>.
        </p>

        <form method="POST" :action="'{{ url('/users') }}/' + id + '/password'" class="space-y-4">
            @csrf
            @method('PATCH')
            <input type="hidden" name="_id" :value="id">
            <input type="hidden" name="_name" :value="name">

            <div>
                <label for="reset-password" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Password Baru</label>
                <input type="password" id="reset-password" name="password" minlength="8" required autocomplete="new-password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('password', 'resetPassword') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="reset-password-confirmation" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password Baru</label>
                <input type="password" id="reset-password-confirmation" name="password_confirmation" required autocomplete="new-password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <x-ui.button type="button" variant="secondary" @click="$store.modal.close()">Batal</x-ui.button>
                <x-ui.button type="submit" variant="primary">Simpan Password</x-ui.button>
            </div>
        </form>
    </div>
</x-ui.modal>