@extends('layouts.app')

@section('title', 'Booking - ' . $lapangan->nama)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            @if($lapangan->gambar)
                <img src="{{ asset('storage/' . $lapangan->gambar) }}" 
                     class="img-fluid rounded shadow" alt="{{ $lapangan->nama }}">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" style="height: 400px;">
                    <i class="fas fa-futbol fa-4x text-secondary"></i>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lapangan.index') }}">Lapangan</a></li>
                    <li class="breadcrumb-item active">{{ $lapangan->nama }}</li>
                </ol>
            </nav>

            <h1 class="fw-bold">{{ $lapangan->nama }}</h1>
            <p class="text-muted">
                <i class="fas fa-map-marker-alt me-2"></i> {{ $lapangan->lokasi }}
            </p>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Harga:</strong> Rp {{ number_format($lapangan->harga, 0, ',', '.') }}/jam
                @if($lapangan->kapasitas)
                    | <strong>Kapasitas:</strong> {{ $lapangan->kapasitas }} orang
                @endif
            </div>

            <div class="mt-4">
                <h5>Fasilitas</h5>
                <ul>
                    <li>Lapangan sintetis berkualitas</li>
                    <li>Lampu penerangan (malam hari)</li>
                    <li>Toilet dan ruang ganti</li>
                    <li>Parkir luas</li>
                </ul>
            </div>

            <div class="mt-4">
                <a href="{{ route('booking.order-now', $lapangan) }}" 
                   class="btn btn-brand w-100 py-3">
                    <i class="fas fa-calendar-check me-2"></i> Booking Lapangan Ini
                </a>
                
                @auth
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $lapangan->id }}">
                        <input type="hidden" name="name" value="{{ $lapangan->nama }} (Booking)">
                        <input type="hidden" name="price" value="{{ $lapangan->harga }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-outline-brand w-100">
                            <i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection