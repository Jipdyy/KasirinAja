@props(['title' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900" x-data="{}">

    <x-layout.navbar />
    <x-layout.sidebar />

    <main class="pt-16 lg:pl-64 min-h-screen">
        <div class="p-4 md:p-6">
            {{ $slot }}
        </div>
    </main>

    <div class="fixed top-20 right-4 z-50 space-y-2">
        {{-- TODO: <x-ui.flash-message /> --}}
    </div>

</body>
</html>