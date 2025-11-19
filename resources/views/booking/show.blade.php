@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Booking #{{ $booking->id }}</h4>
                    <span class="badge bg-{{ $booking->status == 'menunggu' ? 'warning' : ($booking->status == 'dikonfirmasi' ? 'success' : 'secondary') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-4"><strong>Lapangan</strong></div>
                        <div class="col-8">{{ $booking->lapangan->nama }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4"><strong>Tanggal</strong></div>
                        <div class="col-8">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('l, d F Y') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4"><strong>Jam</strong></div>
                        <div class="col-8">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4"><strong>Durasi</strong></div>
                        <div class="col-8">{{ $booking->durasi }} jam</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4"><strong>Total</strong></div>
                        <div class="col-8">
                            <span class="h5 text-primary fw-bold">
                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('booking.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i> Daftar Booking
                        </a>
                        @if($booking->status == 'menunggu')
                            <a href="{{ route('booking.checkout', $booking) }}" class="btn btn-success">
                                <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection