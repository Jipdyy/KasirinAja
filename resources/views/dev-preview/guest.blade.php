<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 antialiased text-gray-900 dark:text-gray-100">

    <div class="relative min-h-screen flex items-center justify-center overflow-hidden p-4">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-500/10 dark:bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 w-full max-w-d">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xs border border-gray-200 dark:border-gray-700 p-6 md:p-8">

                {{-- Header Login --}}
                <div class="flex flex-col items-center text-center mb-6">
                    {{-- Logo --}}
                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20 dark:bg-blue-500">

                    </div>

                    {{-- Judul & Subjudul Generik --}}
                    <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-2xl">
                        {{ config('app.name', 'Kasirin Aja') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Masuk ke akun Anda 
                    </p>
                </div>

                {{-- Slot Form Login --}}
                {{ $slot ?? '' }}
            </div>
        </div>
    </div>
</body>
</html>