@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mb-4">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 60px; height: 60px;">
                    <i class="fas fa-check fa-2x"></i>
                </div>
                <h2 class="fw-bold text-success">Booking Berhasil!</h2>
                <p class="text-muted">Nomor booking: <strong>#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="https://placehold.co/150x150/22c55e/FFFFFF?text=✓" 
                             alt="Success" 
                             class="img-fluid rounded-circle mb-3"
                             style="width: 80px; height: 80px; object-fit: cover;">
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Detail Booking</h5>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Lapangan</span>
                                <span class="fw-medium">{{ $booking->lapangan->nama }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Jadwal</span>
                                <span class="small text-muted">
                                    {{ $booking->start_time->format('l, d M Y') }}<br>
                                    {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Durasi</span>
                                <span>{{ $booking->duration_hours }} jam</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                                <span>Total Bayar</span>
                                <span class="text-primary">Rp {{ number_format($booking->total_price) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border-0 rounded-3">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-info-circle me-2"></i> Informasi Penting
                        </h6>
                        <ul class="mb-0 small">
                            <li>Silakan datang 15 menit sebelum jadwal</li>
                            <li>Tunjukkan nomor booking ini di loket</li>
                            <li>Simpan bukti pembayaran</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('booking.index') }}" class="btn btn-brand">
                            <i class="fas fa-list me-2"></i> Lihat Semua Booking
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-brand">
                            <i class="fas fa-home me-2"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection