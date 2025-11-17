@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-5">
                <a href="{{ url()->previous() }}" class="btn btn-outline-brand rounded-circle me-3" title="Kembali">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="fw-bold text-brand mb-1">Keranjang Belanja</h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i> 
                        Produk akan disimpan selama 7 hari
                    </p>
                </div>
            </div>

            @if(count($cart))
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <!-- Items List -->
                    <div class="list-group list-group-flush">
                        @php $total = 0; @endphp
                        @foreach($cart as $item)
                            @php
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp
                            <div class="list-group-item px-4 py-3">
                                <div class="d-flex gap-3">
                                    <!-- Gambar -->
                                    <div class="flex-shrink-0">
                                        <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                             style="width: 72px; height: 72px;">
                                            @if(isset($item['image']) && $item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" 
                                                     alt="{{ $item['name'] }}"
                                                     class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fas fa-box-open text-muted fa-2x"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Konten -->
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $item['name'] }}</h6>
                                        <p class="text-muted small mb-2">
                                            @if(isset($item['category']))
                                                <span class="badge bg-light text-dark me-1">
                                                    {{ ucfirst($item['category']) }}
                                                </span>
                                            @endif
                                            {{ Str::limit($item['description'] ?? '', 80) }}
                                        </p>

                                        <!-- Harga & Quantity -->
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold text-primary">Rp {{ number_format($item['price']) }}</span>
                                                
                                                <!-- Quantity Adjust -->
                                                <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center gap-1 mb-0">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                    
                                                    <button type="submit" name="action" value="decrease"
                                                            class="btn btn-sm btn-outline-brand rounded-circle p-0"
                                                            style="width: 28px; height: 28px;"
                                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                                            title="Kurangi">
                                                        <i class="fas fa-minus fa-xs"></i>
                                                    </button>
                                                    
                                                    <span class="fw-medium px-2">{{ $item['quantity'] }}</span>
                                                    
                                                    <button type="submit" name="action" value="increase"
                                                            class="btn btn-sm btn-brand rounded-circle p-0"
                                                            style="width: 28px; height: 28px;"
                                                            title="Tambah">
                                                        <i class="fas fa-plus fa-xs text-white"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Subtotal & Hapus -->
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="text-muted small">Subtotal:</span>
                                                <span class="fw-bold">Rp {{ number_format($subtotal) }}</span>
                                                
                                                <form action="{{ route('cart.remove') }}" method="POST" class="mb-0">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger rounded-circle p-0"
                                                            style="width: 32px; height: 32px;"
                                                            title="Hapus"
                                                            onclick="return confirm('Hapus {{ addslashes($item['name']) }} dari keranjang?')">
                                                        <i class="fas fa-trash-alt fa-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div class="card-footer bg-white border-top">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <div class="bg-light rounded-3 p-3">
                                    <h6 class="fw-bold mb-2">Catatan Tambahan</h6>
                                    <textarea class="form-control form-control-sm" 
                                              placeholder="Contoh: Kirim ke lapangan jam 18.00, tambahkan air mineral..."
                                              rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="border rounded-3 p-4 h-100">
                                    <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                                    
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Item</span>
                                        <span>{{ collect($cart)->sum('quantity') }} item</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mb-3 fw-bold">
                                        <span>Total Bayar</span>
                                        <span class="text-primary fs-4">Rp {{ number_format($total) }}</span>
                                    </div>

                                    <!-- Promo Code -->
                                    <div class="mb-3">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" 
                                                   placeholder="Masukkan kode promo">
                                            <button class="btn btn-outline-brand" type="button">
                                                <i class="fas fa-tag me-1"></i> Terapkan
                                            </button>
                                        </div>
                                        <small class="text-muted">Promo berlaku untuk mitra terpilih</small>
                                    </div>

                                    <a href="{{ route('checkout.index') }}" 
                                       class="btn btn-brand w-100 py-2 fs-5">
                                        <i class="fas fa-shopping-cart me-2"></i> Lanjut ke Pembayaran
                                    </a>

                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-lock me-1"></i> Pembayaran aman via Midtrans
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="alert alert-light border-0 rounded-3 mt-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3 mt-1">
                            <i class="fas fa-lightbulb text-brand fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Tips Sportykuy</h6>
                            <ul class="mb-0 small text-muted">
                                <li>Pesan minimal 1 jam sebelum jadwal main</li>
                                <li>Produk tambahan (minuman, bola) bisa ditambah di lapangan</li>
                                <li>Gratis antar-jemput untuk booking > 3 jam</li>
                            </ul>
                        </div>
                    </div>
                </div>

            @else
                <!-- Keranjang Kosong -->
                <div class="text-center py-5">
                    <div class="position-relative d-inline-block mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-shopping-cart fa-3x text-muted"></i>
                        </div>
                        <div class="position-absolute bottom-0 start-50 translate-middle-x"
                             style="width: 40px; height: 40px; background: #D85C5C; border-radius: 50%;">
                            <i class="fas fa-times text-white fa-lg"></i>
                        </div>
                    </div>
                    
                    <h2 class="fw-bold text-brand mb-2">Keranjangmu Kosong</h2>
                    <p class="text-muted mb-4 px-3">
                        Tambahkan produk favoritmu untuk melengkapi sesi olahraga!
                    </p>
                    
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-brand px-4 py-2">
                            <i class="fas fa-store me-1"></i> Lihat Produk
                        </a>
                        <a href="{{ route('lapangan.index') }}" class="btn btn-outline-brand px-4 py-2">
                            <i class="fas fa-futbol me-1"></i> Booking Lapangan
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-hide success message
document.addEventListener('DOMContentLoaded', function() {
    const alertSuccess = document.querySelector('.alert-success');
    if (alertSuccess) {
        setTimeout(() => {
            alertSuccess.classList.add('fade');
            setTimeout(() => alertSuccess.remove(), 300);
        }, 3000);
    }
});
</script>
@endpush
@endsection