@extends('layouts.app')

@section('content')
<div class="container py-5" x-data="productCart()">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
        <div>
            <h1 class="fw-bold text-brand mb-2">Produk</h1>
            <p class="text-muted mb-0">{{ $products->count() }} produk ditemukan</p>
        </div>

        {{-- ✅ Hanya tampilkan tombol "Tambah" untuk partner --}}
        @if(auth()->check() && auth()->user()->role === 'partner')
            <a href="{{ route('products.create') }}" class="btn btn-brand px-4 py-2 mt-3 mt-md-0">
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
                    <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                        <!-- Gambar Produk -->
                        <div class="position-relative">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x250/e2e8f0/94a3b8?text=+' }}"
     class="card-img-top"
     alt="{{ $product->name }}"
     style="height: 200px; object-fit: cover;">
                            
                            <!-- Badge Kategori -->
                            <span class="position-absolute top-0 start-0 bg-accent text-white fw-bold px-2 py-1 small">
                                @if($product->category == 'alat') Alat
                                @elseif($product->category == 'makanan') Makanan
                                @else Merchandise
                                @endif
                            </span>

                            <!-- Stok Badge -->
                            @if($product->stock <= 5 && $product->stock > 0)
                                <span class="position-absolute top-0 end-0 bg-warning text-dark fw-bold px-2 py-1 small">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Sisa {{ $product->stock }}
                                </span>
                            @elseif($product->stock == 0)
                                <span class="position-absolute top-0 end-0 bg-danger text-white fw-bold px-2 py-1 small">
                                    <i class="fas fa-times me-1"></i> Habis
                                </span>
                            @endif
                        </div>

                        <!-- Konten -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($product->description, 80) }}
                            </p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 fw-bold text-primary mb-0">Rp {{ number_format($product->price) }}</span>
                                    @if($product->stock > 0)
                                        <span class="badge bg-light text-dark fw-normal">
                                            <i class="fas fa-boxes me-1"></i> {{ $product->stock }} stok
                                        </span>
                                    @endif
                                </div>

                                <!-- ✅ Tombol Aksi: Beda antara partner & user -->
                                <div class="d-flex gap-2">
                                    @if(auth()->check() && in_array(auth()->user()->role, ['partner', 'super_admin']))
                                        {{-- Partner/Super Admin: edit & hapus --}}
                                        <a href="{{ route('products.edit', $product->id) }}" 
                                           class="btn btn-outline-brand flex-fill" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('products.destroy', $product->id) }}" 
                                              method="POST" class="flex-fill"
                                              onsubmit="return confirm('Yakin hapus {{ addslashes($product->name) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger w-100" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @else
                                        {{-- User biasa/guest: tambah ke keranjang --}}
                                        <button 
                                            @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                                            :disabled="adding[{{ $product->id }}]"
                                            class="btn btn-brand flex-fill"
                                            :class="adding[{{ $product->id }}] ? 'opacity-75' : ''">
                                            <template x-if="!adding[{{ $product->id }}]">
                                                <i class="fas fa-cart-plus me-1"></i> Tambah
                                            </template>
                                            <template x-if="adding[{{ $product->id }}]">
                                                <span class="spinner-border spinner-border-sm" role="status"></span>
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

        @if ($products->hasPages())
            <div class="mt-5">
                {{ $products->links() }}
            </div>
        @endif

    @else
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
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
                <a href="{{ route('products.create') }}" class="btn btn-brand px-4 py-2">
                    <i class="fas fa-plus me-1"></i> Tambah Produk Pertama
                </a>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productCart', () => ({
        adding: {},

        addToCart(productId, name, price) {
            this.adding[productId] = true;

            // ✅ Pakai NAMED ROUTE 'cart.add' — SUDAH AMAN karena route sudah ada di web.php
            fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    name: name,
                    price: price,
                    quantity: 1
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Response not OK');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // Trigger update cart di navbar (jika pakai Alpine global)
                    document.dispatchEvent(new CustomEvent('cart-updated'));
                    
                    // Notifikasi sukses
                    alert(`${name} berhasil ditambahkan ke keranjang!`);
                } else {
                    throw new Error('Server response: success=false');
                }
            })
            .catch(err => {
                console.error('Cart add error:', err);
                alert(`Gagal menambahkan ${name} ke keranjang.\nCoba lagi nanti.`);
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