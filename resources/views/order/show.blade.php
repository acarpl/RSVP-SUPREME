@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Pesanan #{{ $order->order_number }}</h2>
        <span class="badge bg-{{ $order->status === 'dibayar' ? 'success' : ($order->status === 'dibatalkan' ? 'danger' : 'warning') }}">
            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Detail Pesanan</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                    <p><strong>Status Pembayaran:</strong> 
                        <span class="badge bg-{{ $order->payment_status === 'settlement' ? 'success' : 'secondary' }}">
                            {{ $order->payment_status === 'settlement' ? 'Lunas' : ucfirst($order->payment_status) }}
                        </span>
                    </p>
                    <p><strong>Metode:</strong> Midtrans</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Produk</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        @if($item->product->gambar)
                                            <img src="{{ asset('storage/' . $item->product->gambar) }}" 
                                                 alt="" width="40" class="rounded me-2">
                                        @endif
                                        {{ $item->product->name }}
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Pengiriman</h5>
                </div>
                <div class="card-body">
                    <p><strong>Alamat:</strong><br>{{ $order->alamat }}</p>
                    @if($order->catatan)
                        <p><strong>Catatan:</strong> {{ $order->catatan }}</p>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>Rincian Biaya</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Total:</span>
                        <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if($order->status === 'menunggu_pembayaran')
<script>
// Cek status tiap 5 detik
setInterval(() => {
    fetch("{{ route('order.show', $order) }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'dibayar') {
                location.reload(); // refresh halaman
            }
        })
        .catch(() => {});
}, 5000);
</script>
@endif
@endsection