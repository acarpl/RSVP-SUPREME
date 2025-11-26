@extends('layouts.app')

@section('content')
<div class="container py-5" x-data="productCart()" id="product-list">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
        <div>
            <h1 class="fw-bold text-brand mb-2">Produk</h1>
            <p class="text-muted mb-0">{{ $products->count() }} produk ditemukan</p>
        </div>

        {{-- ✅ Hanya tampilkan tombol "Tambah" untuk partner --}}
        @if(auth()->check() && auth()->user()->role === 'partner')
            <a href="{{ route('partner.products.create') }}" class="btn btn-brand px-4 py-2 mt-3 mt-md-0">
                <i class="fas fa-plus me-1"></i> Tambah Produk
            </a>
        @endif
    </div>

    @if ($products->count())
        <!-- Filter Kategori -->
        <div class="mb-4">
            <div class="btn-group" role="group" aria-label="Filter kategori">
                <a href="{{ route('products.index') }}" class="btn btn-outline-brand {{ !request('category') ? 'active' : '' }}">
                    Semua
                </a>
                <a href="{{ route('products.index', ['category' => 'alat']) }}" class="btn btn-outline-brand {{ request('category') == 'alat' ? 'active' : '' }}">
                    <i class="fas fa-futbol me-1"></i> Alat
                </a>
                <a href="{{ route('products.index', ['category' => 'makanan']) }}" class="btn btn-outline-brand {{ request('category') == 'makanan' ? 'active' : '' }}">
                    <i class="fas fa-utensils me-1"></i> Makanan
                </a>
                <a href="{{ route('products.index', ['category' => 'merchandise']) }}" class="btn btn-outline-brand {{ request('category') == 'merchandise' ? 'active' : '' }}">
                    <i class="fas fa-tshirt me-1"></i> Merchandise
                </a>
            </div>
        </div>

        <!-- Grid Produk -->
        <div class="row g-4">
            @foreach ($products as $product)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden transition-shadow"
                         style="transition: box-shadow 0.3s ease;">
                        <!-- Gambar Produk -->
                        <div class="ratio ratio-16x9 bg-light">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/e2e8f0/94a3b8?text=No+Image  ' }}"
                                 class="object-fit-cover w-100 h-100"
                                 alt="{{ $product->name }}"
                                 loading="lazy">
                            
                            <!-- Badge Kategori (Top-Left) -->
                            <span class="position-absolute top-2 start-2 bg-accent text-white fw-bold px-2 py-1 rounded-2 small z-1">
                                @if($product->category == 'alat') Alat
                                @elseif($product->category == 'makanan') Makanan
                                @else Merchandise
                                @endif
                            </span>

                            <!-- Stok Badge (Top-Right) -->
                            @if($product->stock == 0)
                                <span class="position-absolute top-2 end-2 bg-danger text-white fw-bold px-2 py-1 rounded-2 small z-1">
                                    <i class="fas fa-times me-1"></i> Habis
                                </span>
                            @elseif($product->stock <= 5)
                                <span class="position-absolute top-2 end-2 bg-warning text-dark fw-bold px-2 py-1 rounded-2 small z-1">
                                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $product->stock }} stok
                                </span>
                            @endif
                        </div>

                        <!-- Konten -->
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                            
                            <p class="card-text text-muted small mb-2 flex-grow-1">
                                {{ Str::limit($product->description, 80) }}
                            </p>

                            <!-- ✅ Nama Partner (jika ada relasi) -->
                            @if($product->partner)
                                <p class="text-sm text-muted mb-2">
                                    <i class="fas fa-store me-1 text-muted"></i>
                                    <span class="fw-medium">{{ $product->partner->name }}</span>
                                </p>
                            @endif

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 fw-bold text-brand mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @if($product->stock > 0 && $product->stock > 5)
                                        <span class="badge bg-light text-dark fw-normal">
                                            <i class="fas fa-boxes me-1"></i> {{ $product->stock }} stok
                                        </span>
                                    @endif
                                </div>

                                <!-- ✅ Tombol Aksi -->
                                <div class="d-grid gap-2">
                                    @if(auth()->check() && in_array(auth()->user()->role, ['partner', 'super_admin']))
                                        {{-- Partner/Super Admin: Edit & Hapus --}}
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('partner.products.edit', $product->id) }}" 
                                               class="btn btn-outline-brand flex-fill"
                                               title="Edit {{ $product->name }}">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            
                                            <form action="{{ route('partner.products.destroy', $product->id) }}" 
                                                  method="POST" class="flex-fill"
                                                  onsubmit="return confirm('Yakin hapus \"{{ addslashes($product->name) }}\"?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger flex-fill">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        {{-- User biasa/guest: Tambah ke keranjang --}}
                                        <button 
                                            @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                                            :disabled="adding[{{ $product->id }}]"
                                            class="btn btn-brand"
                                            :class="adding[{{ $product->id }}] ? 'disabled opacity-75' : ''">
                                            <template x-if="!adding[{{ $product->id }}]">
                                                <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                                            </template>
                                            <template x-if="adding[{{ $product->id }}]">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Sedang ditambahkan...
                                            </template>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ✅ PAGINATION FIX: TETAP DI POSISI & SCROLL SMOOTH -->
        @if ($products->hasPages())
            <div class="mt-5" id="pagination-container">
                {{ $products->appends(request()->query())->fragment('product-list')->links() }}
            </div>
        @endif

    @else
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                 style="width: 80px; height: 80px;">
                <i class="fas fa-box-open fa-2x text-muted"></i>
            </div>
            <h3 class="fw-bold text-muted mb-2">Belum Ada Produk</h3>
            <p class="text-muted mb-4">
                @if(auth()->check() && in_array(auth()->user()->role, ['partner', 'super_admin']))
                    Mulai tambahkan produk untuk venue-mu.
                @else
                    Produk akan segera tersedia.
                @endif
            </p>

            @if(auth()->check() && in_array(auth()->user()->role, ['partner', 'super_admin']))
                <a href="{{ route('partner.products.create') }}" class="btn btn-brand px-4 py-2">
                    <i class="fas fa-plus me-1"></i> Tambah Produk Pertama
                </a>
            @endif
        </div>
    @endif
</div>

<style>
    .transition-shadow:hover {
        box-shadow: 0 0.75rem 1.5rem rgba(216, 92, 92, 0.15) !important;
    }
    .ratio-16x9 {
        --bs-aspect-ratio: 62.5 %; /* 16:10 → lebih tinggi dari 16:9 untuk tampilan produk */
    }
    .object-fit-cover {
        object-fit: cover;
    }

    /* ✅ SCROLL SMOOTH UNTUK ANCHOR */
    html {
        scroll-behavior: smooth;
    }
</style>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productCart', () => ({
        adding: {},

        addToCart(productId, name, price) {
            if (this.adding[productId]) return;
            
            this.adding[productId] = true;

            const formData = new FormData();
            formData.append('quantity', 1);

            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Trigger event untuk update UI (e.g., badge keranjang)
                    document.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                    // Toast atau alert halus (opsional: ganti dengan Toast Bootstrap)
                    alert('✅ ' + data.message);
                } else {
                    throw new Error(data.message || 'Gagal menambahkan.');
                }
            })
            .catch(err => {
                console.error('Cart error:', err);
                alert('❌ Gagal menambahkan ke keranjang. Silakan coba lagi.');
            })
            .finally(() => {
                this.adding[productId] = false;
            });
        }
    }));
});
</script>
@endpush
@endsection