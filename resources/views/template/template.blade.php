<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPORTYKUY')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts & Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap');

        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-weight: 400;
            margin: 0;
            padding: 0;
        }

        .bold-font {
            font-weight: 700;
        }

        /* Navbar */
        .navbar-custom {
            background-color: #D85C5C !important;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #fff !important;
            font-weight: 500;
        }

        .navbar-custom .nav-link:hover {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        /* Button umum */
        .btn {
            border-radius: 100px;
            margin: 5px;
        }

        /* Warna utama */
        .text-custom-red {
            color: #D85C5C !important;
        }

        /* Hero Section (buat index.blade) */
        .hero-section {
            background-color: #D85C5C;
            color: white;
            text-align: center;
            padding: 5rem 1rem;
            margin-top: -1rem;
        }

        .hero-section h2 {
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* Footer */
        footer {
            background-color: #f8f9fa;
            color: #333;
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    @include('template.navigation')

    {{-- Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('template.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
