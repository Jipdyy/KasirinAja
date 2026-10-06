<x-layouts.guest>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-rose-50 text-rose-600 text-sm dark:bg-rose-900/30 dark:text-rose-400">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Email</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
        </div>

        <div>
            <label for="password" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Password</label>
            <input
                type="password"
                name="password"
                id="password"
                required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
        </div>

        <x-ui.button type="submit" variant="primary" class="w-full">
            Masuk
        </x-ui.button>
    </form>

</x-layouts.guest>