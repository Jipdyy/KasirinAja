 <header class="fixed top-0 left-0 right-0 z-40 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="h-16 px-4 flex items-center justify-between">

        <div class="flex items-center">
            <button
                type="button"
                @click="$store.sidebar.open = !$store.sidebar.open"
                class="block p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                aria-label="Buka menu navigasi"
            >
                <img src="{{ asset('images/logo.png') }}" alt="Kafetaria" class="w-10 h-10">
            </button>
            <span class="text-xl font-bold dark:text-gray-200">Kantin Smart</span>
        </div>

        <div class="flex items-center gap-3">

            {{-- Profil --}}
            <div x-data="{ open: false }" class="relative flex items-center">
                <button
                    type="button"
                    @click="open = !open"
                    class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-semibold">
                        P
                    </span>
                    <span class="hidden sm:flex flex-col items-start text-left">
                        <span class="text-sm font-medium text-gray-900 dark:text-white leading-tight">
                            {{ auth()->user()->name ?? 'Pengguna'}}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 leading-tight">
                            Admin
                        </span>
                    </span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div 
                    x-show="open" 
                    @click.outside="open = false"
                    x-transition
                    style="display: none;"
                    class="absolute right-0 top-full mt-2 z-50 w-44 bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600"
                >
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                        <li>
                            <form method="POST" action="">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                
            </div>
        </div>

    </div>
</header>