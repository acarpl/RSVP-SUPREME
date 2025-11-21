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

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-weight: 400;
        }

        .bg-brand { background-color: #D85C5C !important; }

        .text-brand { color: #D85C5C !important; }

        .border-brand { border-color: #D85C5C !important; }
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

        /* Section Kaburajadulu */
        .section-kabur {
            background-color: #D85C5C;
            color: white;
            padding: 4rem 0;
        }

        /* View More Button */
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

        /* Field Card */
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

        /* Warna biru untuk harga */
        .text-primary { color: #0040ff !important; }

        /* Dropdown Cart */
        [x-cloak] { display: none !important; }

        /* ============================
   RESPONSIVE MOBILE IMPROVEMENTS
   ============================ */
@media (max-width: 576px) {

    /* Padding halaman */
    main.container {
        padding-left: 12px !important;
        padding-right: 12px !important;
    }

    /* Heading lebih kecil */
    h1, h2, h3 {
        font-size: 1.3rem !important;
    }

    h4, h5 {
        font-size: 1.05rem !important;
    }

    /* Card lebih slim */
    .card {
        border-radius: 14px !important;
        padding: 0.75rem !important;
    }

    /* Form dan input */
    .form-control,
    .form-select {
        font-size: 0.9rem !important;
        padding: 0.55rem 0.75rem !important;
    }

    button.btn,
    .btn {
        font-size: 0.9rem !important;
        padding: 0.55rem !important;
        border-radius: 10px !important;
    }

    /* Footer kecil */
    footer {
        font-size: 0.75rem;
    }

    /* Jarak konten bawah biar ga numpuk sama bottom nav */
    body {
        padding-bottom: 90px !important;
    }

    /* Navbar mobile icons */
    .bottom-nav .nav-link i {
        font-size: 1.1rem !important;
    }

    .bottom-nav .nav-link span {
        font-size: 0.7rem !important;
    }
}

    </style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="/">SPORTYKUY</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav d-flex align-items-center gap-2">

                    <li class="nav-item">
                        <a class="nav-link" href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/lapangan">Lapangan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products">Produk</a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endguest

                    @auth
                        {{-- 🛒 Cart Icon (hanya muncul jika route cart ada) --}}
                        @if (Route::has('cart.count'))
                            <li class="nav-item position-relative" x-data="cartDropdown()" x-init="init()" x-cloak>
                                <button @click="toggle"
                                        class="nav-link text-white p-1 position-relative"
                                        aria-label="Keranjang">
                                    <i class="fas fa-shopping-cart fa-lg"></i>
                                    <span x-show="count > 0" 
                                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                          style="font-size: 0.7rem; padding: 0.3em 0.5em;">
                                        <span x-text="count"></span>
                                    </span>
                                </button>

                                <!-- Dropdown Cart -->
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="position-absolute top-100 end-0 mt-2 w-72 bg-white rounded-3 shadow-lg border z-3 py-2">
                                    
                                    <div class="px-3 pb-2 border-bottom d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0">Keranjang (<span x-text="count"></span>)</h6>
                                        <small x-show="count > 0" class="text-muted">
                                            <span x-text="totalItems"></span> item
                                        </small>
                                    </div>

                                    <!-- Kosong -->
                                    <div x-show="count === 0" class="px-4 py-5 text-center">
                                        <i class="fas fa-shopping-bag fa-2x text-secondary mb-2"></i>
                                        <p class="text-muted mb-0">Keranjang masih kosong</p>
                                    </div>

                                    <!-- Isi -->
                                    <ul x-show="count > 0" class="list-group list-group-flush">
                                        <template x-for="item in items" :key="item.id">
                                            <li class="list-group-item px-3 py-2">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <div class="fw-medium" x-text="item.name"></div>
                                                        <small class="text-muted" x-text="`Rp ${item.price.toLocaleString()}`"></small>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light text-dark" x-text="item.quantity"></span>
                                                        <button @click="remove(item.id)"
                                                                class="btn btn-sm btn-outline-danger rounded-circle p-0"
                                                                style="width: 24px; height: 24px;"
                                                                title="Hapus">
                                                            <i class="fas fa-times fa-xs"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>

                                    <!-- Footer -->
                                    <div x-show="count > 0" class="px-3 pt-2 border-top">
                                        <div class="d-flex justify-content-between fw-bold mb-2">
                                            <span>Total:</span>
                                            <span class="text-primary" x-text="`Rp ${totalPrice.toLocaleString()}`"></span>
                                        </div>
                                        <a href="{{ Route::has('cart.index') ? route('cart.index') : '#' }}" 
                                           class="btn btn-brand w-100 btn-sm">
                                            <i class="fas fa-shopping-cart me-1"></i> Lihat & Bayar
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endif

                        <!-- 👤 User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="rounded-circle bg-light text-brand fw-bold d-flex align-items-center justify-content-center me-2"
                                      style="width: 36px; height: 36px; font-size: 1rem;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
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

    <!-- Konten Utama -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light border-top mt-auto">
        <div class="container py-3 text-center text-muted">
            <p class="mb-0">&copy; {{ date('Y') }} Sportykuy. SMKN 1 Kota Bekasi UKOM RPL A 27.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Cart Script (aman jika route tidak ada) -->
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cartDropdown', () => ({
            open: false,
            items: [],
            count: 0,
            totalItems: 0,
            totalPrice: 0,

            init() {
                this.loadCart();
            },

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.loadCart();
                }
            },

            loadCart() {
                // Cek apakah route cart.count tersedia
                fetch("{{ Route::has('cart.count') ? route('cart.count') : '#' }}")
                    .then(res => {
                        if (res.ok && res.url !== location.origin + '/') {
                            return res.json();
                        } else {
                            throw new Error('Cart route not available');
                        }
                    })
                    .then(data => {
                        this.count = data.total_items || 0;
                        this.totalItems = data.total_items || 0;
                        this.totalPrice = data.total_price || 0;
                        this.items = data.items || [];
                    })
                    .catch(err => {
                        // Silent fail — tidak munculkan error di console
                        this.count = 0;
                        this.items = [];
                    });
            },

            remove(productId) {
                if (!confirm('Hapus dari keranjang?')) return;

                const url = "{{ Route::has('cart.remove') ? route('cart.remove') : '#' }}";
                if (url === '#') return;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.loadCart();
                    }
                });
            }
        }));
    });
    </script>

    @stack('scripts')
</body>
</html>