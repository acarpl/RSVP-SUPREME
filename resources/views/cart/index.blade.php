@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="fw-bold text-brand mb-0">Keranjang Belanja</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(empty($items))
    <div class="text-center py-5">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
            style="width: 80px; height: 80px;">
            <i class="fas fa-shopping-cart fa-2x text-muted"></i>
        </div>
        <h3 class="fw-bold text-muted mb-2">Keranjang Kosong</h3>
        <p class="text-muted mb-4">Tidak ada produk di keranjangmu.</p>
        <a href="{{ route('products.index') }}" class="btn btn-brand px-4 py-2">
            <i class="fas fa-store me-1"></i> Lihat Produk
        </a>
    </div>
    @else
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-list me-2"></i>
                        Produk ({{ count($items) }} item)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50%">Produk</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Jumlah</th>
                                <th width="15%">Subtotal</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item['product']->gambar)
                                        <img src="{{ asset('storage/' . $item['product']->gambar) }}"
                                            class="rounded me-3" width="50" height="50"
                                            alt="{{ $item['product']->name }}">
                                        @else
                                        <div class="bg-light border rounded me-3 d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $item['product']->name }}</div>
                                            <small class="text-muted">Stok: {{ $item['product']->stock }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold text-primary">
                                    Rp {{ number_format($item['product']->price) }}
                                </td>
                                <td>
                                    @if($item['product']->stock > 0)
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <input type="number"
                                            name="quantity"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            max="{{ $item['product']->stock }}"
                                            class="form-control form-control-sm text-center"
                                            style="width: 70px;"
                                            onchange="this.form.submit()">
                                    </form>
                                    @else
                                    <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    Rp {{ number_format($item['subtotal']) }}
                                </td>
                                <td>
                                    <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus"
                                            onclick="return confirm('Hapus {{ addslashes($item['product']->name) }} dari keranjang?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-brand">
                    <i class="fas fa-arrow-left me-1"></i> Lanjut Belanja
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-receipt me-2"></i>
                        Ringkasan Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Produk:</span>
                        <span>{{ count($items) }} item</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span class="fw-bold">Rp {{ number_format($total) }}</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                        <span>Total:</span>
                        <span class="text-primary">Rp {{ number_format($total) }}</span>
                    </div>

                    <div class="alert alert-light small p-2 mb-3">
                        <i class="fas fa-shield-alt me-1 text-success"></i>
                        Pembayaran aman via Midtrans
                    </div>

                    <div class="d-grid gap-2">
                        <!-- ✅ SESUAI STRUKTUR ANDA: checkout dari keranjang -->
                        <a href="{{ route('checkout.index') }}" class="btn btn-brand btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Lanjut ke Checkout
                        </a>

                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-plus me-1"></i> Tambah Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection