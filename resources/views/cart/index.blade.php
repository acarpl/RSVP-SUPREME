@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Keranjang Belanja</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(empty($items))
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h5>Keranjang kosong</h5>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Lihat Produk</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Daftar Barang ({{ count($items) }} item)</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>
                                            @if($item['product']->gambar)
                                                <img src="{{ asset('storage/' . $item['product']->gambar) }}" 
                                                     alt="" width="40" class="rounded me-2">
                                            @endif
                                            {{ $item['product']->name }}
                                        </td>
                                        <td>Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                <input type="number" name="quantity" 
                                                       value="{{ $item['quantity'] }}" 
                                                       min="1" max="{{ $item['product']->stock }}"
                                                       class="form-control form-control-sm d-inline w-auto"
                                                       style="width: 70px;"
                                                       onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">&laquo; Lanjut Belanja</a>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Rincian Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total:</span>
                            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <hr>
                        <a href="{{ route('checkout.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-shopping-check"></i> Lanjut ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection