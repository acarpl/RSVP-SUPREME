<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sportykuy') }}</title>

    <!-- Tailwind -->
    @vite('resources/css/app.css')

    <!-- AlpineJS untuk dropdown navbar -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="font-poppins bg-gray-50 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Konten utama -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer global -->
        <footer class="bg-white text-gray-600 text-center py-6 border-t mt-10">
            <p>© 2025 Sportykuy. Semua hak dilindungi.</p>
        </footer>
    </div>
</body>
</html>
