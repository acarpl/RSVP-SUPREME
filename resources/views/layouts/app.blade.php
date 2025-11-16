<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPORTYKUY')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts: Plus Jakarta Sans & Bebas Neue -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-weight: 400;
        }

        .bold-font {
            font-weight: 700;
        }

        .bg-brand {
            background-color: #D85C5C !important;
        }
        .text-brand {
            color: #D85C5C !important;
        }
        .btn-brand {
            background-color: #D85C5C;
            border-color: #D85C5C;
            color: white;
            border-radius: 100px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }
        .btn-brand:hover {
            background-color: #c24a4a;
            border-color: #c24a4a;
            color: white;
        }

        .btn-outline-brand {
            color: #D85C5C;
            border-color: #D85C5C;
            border-radius: 100px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }
        .btn-outline-brand:hover {
            background-color: #D85C5C;
            color: white;
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

        .hero-section {
            background-color: #D85C5C;
            color: white;
            text-align: center;
            padding: 5rem 1rem;
            margin-top: -1rem;
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

        .section-kabur {
            background-color: #D85C5C;
            color: white;
            padding: 4rem 0;
        }
        .section-kabur h2 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
        }

        .btn-view-more {
            background-color: #fff;
            color: #D85C5C;
            border: 2px solid #D85C5C;
            border-radius: 20px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-view-more:hover {
            background-color: #D85C5C;
            color: white;
        }

        .field-card {
    background-color: #FF7A3F;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}
.field-card:hover {
    transform: translateY(-5px);
}

.btn-brand {
    background-color: #D85C5C;
    border-color: #D85C5C;
    color: white;
    border-radius: 100px;
    font-weight: 600;
}
.btn-brand:hover {
    background-color: #c24a4a;
}

.btn-outline-brand {
    color: #D85C5C;
    border-color: #D85C5C;
    border-radius: 100px;
    font-weight: 600;
}
.btn-outline-brand:hover {
    background-color: #D85C5C;
    color: white;
}

.text-primary { color: #0040ff !important; }
    </style>

    <!-- AlpineJS (opsional, untuk dropdown jika tetap pakai) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
</head>
<body>

    {{-- 🔴 Navbar — kita buat inline dulu (biar cepat), nanti bisa dipisah --}}
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="/">SPORTYKUY</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/lapangan">Lapangan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/produk">Produk</a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm mx-1" href="{{ route('register') }}">Daftar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm mx-1" href="{{ route('login') }}">Login</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="rounded-circle bg-light text-brand fw-bold d-flex align-items-center justify-content-center me-2"
                                      style="width: 36px; height: 36px; font-size: 1rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user me-2"></i> Profil Saya
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten utama --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer>
        <p class="mb-0">&copy; {{ date('Y') }} Sportykuy. SMKN 1 Kota Bekasi UKOM RPL A 27.</p>
    </footer>

    <!-- Bootstrap JS (wajib untuk dropdown, navbar toggle) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>