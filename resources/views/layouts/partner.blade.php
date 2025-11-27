<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            background-color: white;
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
            main.container {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            h1, h2, h3 {
                font-size: 1.3rem !important;
            }

            h4, h5 {
                font-size: 1.05rem !important;
            }

            .card {
                border-radius: 14px !important;
                padding: 0.75rem !important;
            }

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

            footer {
                font-size: 0.75rem;
            }

            body {
                padding-bottom: 90px !important;
            }

            .bottom-nav .nav-link i {
                font-size: 1.1rem !important;
            }

            .bottom-nav .nav-link span {
                font-size: 0.7rem !important;
            }
        }
    </style>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
</head>
<body>
    
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

    <!-- Cart Script -->
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
                const cartCountRoute = "{{ Route::has('cart.count') ? route('cart.count') : '' }}";
                if (!cartCountRoute) {
                    this.count = 0;
                    this.items = [];
                    return;
                }

                fetch(cartCountRoute)
                    .then(res => res.json())
                    .then(data => {
                        this.count = data.total_items || 0;
                        this.totalItems = data.total_items || 0;
                        this.totalPrice = data.total_price || 0;
                        this.items = data.items || [];
                    })
                    .catch(() => {
                        this.count = 0;
                        this.items = [];
                    });
            },

            remove(productId) {
                if (!confirm('Hapus dari keranjang?')) return;

                fetch(`/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.loadCart();
                    }
                })
                .catch(err => console.error('Cart remove error:', err));
            }
        }));
    });
    </script>

    @stack('scripts')
</body>
</html>