@extends('layouts.app')

@section('title', 'Checkout Booking')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Konfirmasi Booking</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ $booking->lapangan->nama }}</h5>
                        <p class="text-muted">
                            <i class="fas fa-calendar me-2"></i> {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('l, d F Y') }}<br>
                            <i class="fas fa-clock me-2"></i> {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }} ({{ $booking->durasi }} jam)
                        </p>
                    </div>

                    <div class="alert alert-primary">
                        <h5>Total Pembayaran</h5>
                        <h2 class="mb-0">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</h2>
                    </div>

                    <div class="mb-4">
                        <h5>Metode Pembayaran</h5>
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="bank" checked>
                                    <label class="form-check-label" for="bank">
                                        <i class="fab fa-cc-visa me-2 text-primary"></i> Transfer Bank
                                    </label>
                                    <div class="mt-2 ms-4">
                                        <p class="mb-1"><strong>BCA</strong>: 1234567890 a/n SPORTYKUY</p>
                                        <p class="mb-1"><strong>Mandiri</strong>: 0987654321 a/n SPORTYKUY</p>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="ewallet">
                                    <label class="form-check-label" for="ewallet">
                                        <i class="fab fa-google-wallet me-2 text-success"></i> E-Wallet (DANA/OVO)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Lakukan pembayaran dalam <strong>1x24 jam</strong>, lalu konfirmasi di halaman ini.
                    </div>

                    <form action="{{ route('booking.confirm', $booking) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 py-3">
                            <i class="fas fa-check-circle me-2"></i> Konfirmasi Pembayaran
                        </button>
                    </form>

                    <a href="{{ route('booking.cancel', $booking) }}" 
                       class="btn btn-outline-danger w-100 mt-3"
                       onclick="return confirm('Yakin batalkan booking ini?')">
                        <i class="fas fa-times-circle me-2"></i> Batalkan Booking
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection