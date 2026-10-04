<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{-- TODO: judul dinamis per halaman --}}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">

    <x-layout.navbar />

    {{-- <x-layout.sidebar /> --}}

    <main class="pt-16 lg:pl-64 min-h-screen">
        <div class="p-4 md:p-6">
            {{ $slot ?? '' }}
            {{-- atau: @yield('content') --}}
        </div>
    </main>

    <div class="fixed top-20 right-4 z-50 space-y-2">
        {{-- TODO: <x-ui.flash-message /> — dibuat di Tahap 2 --}}
    </div>

</body>
</html>