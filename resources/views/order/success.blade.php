@extends('layouts.app')
@section('content')
<div class="container py-5 text-center">
    <div class="alert alert-success">
        <h2>✅ Pesanan Berhasil!</h2>
        <p>Nomor Order: <strong>{{ $order->order_number }}</strong></p>
        <p>Total: <strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong></p>
    </div>
    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection