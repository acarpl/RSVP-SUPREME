<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPORTYKUY')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manufacturing+Consent&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
        }
        .bold-font {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-style: bold;
        }
        .blue-grid {
            background-color: #0047AB;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .navbar-custom {
            background-color: #D85C5C !important;
        }   

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white !important;
        }

        .navbar-custom .nav-link:hover {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .text-custom-red {
            color: #D85C5C !important;
        }

        .btn {
            border-radius: 100px;
            margin: 5px;
        }
        .navbar-custom {
            background-color: #D85C5C !important;
        }

        .navbar-custom .nav-link {
            color: white !important;
        }

        .navbar-custom .nav-link:hover {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .text-custom-red {
            color: #D85C5C !important;
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