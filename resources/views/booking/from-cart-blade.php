@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-brand rounded-circle me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="fw-bold text-brand mb-1">Booking dari Keranjang</h1>
                    <p class="text-muted mb-0">{{ count($cartItems) }} item siap dibooking</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('booking.cart.store') }}">
    @csrf

    <!-- Pilih Lapangan -->
    <div class="mb-4">
        <label class="form-label fw-medium">Pilih Lapangan</label>
        <select name="lapangan_id" class="form-select form-select-lg" required>
            <option value="">Pilih lapangan</option>
            @foreach($lapangans as $lapangan)
                <option value="{{ $lapangan->id }}">
                    {{ $lapangan->nama }} — Rp {{ number_format($lapangan->harga, 0, ',', '.') }}/jam
                </option>
            @endforeach
        </select>
    </div>

    <!-- Waktu Booking -->
    <div class="mb-4">
        <label class="form-label fw-medium">Jadwal Booking</label>
        <div class="row g-3">
            <div class="col-12">
                <input type="date" 
                       name="tanggal" 
                       class="form-control form-control-lg"
                       value="{{ old('tanggal', date('Y-m-d')) }}"
                       min="{{ date('Y-m-d') }}"
                       required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jam Mulai</label>
                <select name="jam_mulai" class="form-select" required>
                    @for ($h = 8; $h <= 20; $h++)
                        @for ($m = 0; $m < 60; $m += 30)
                            @php $time = sprintf('%02d:%02d', $h, $m); @endphp
                            <option value="{{ $time }}" {{ old('jam_mulai') == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                        @endfor
                    @endfor
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Durasi</label>
                <select name="durasi" class="form-select" required>
                    <option value="1" {{ old('durasi') == 1 ? 'selected' : '' }}>1 jam</option>
                    <option value="2" {{ old('durasi') == 2 ? 'selected' : '' }}>2 jam</option>
                    <option value="3" {{ old('durasi') == 3 ? 'selected' : '' }}>3 jam</option>
                    <option value="4" {{ old('durasi') == 4 ? 'selected' : '' }}>4 jam</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Ringkasan Keranjang -->
    <div class="border-top pt-3">
        <h5 class="mb-3">Produk dari Keranjang</h5>
        @foreach($cartItems as $item)
            <div class="d-flex justify-content-between mb-2">
                <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                <span>Rp {{ number_format($item['price'] * $item['quantity']) }}</span>
            </div>
        @endforeach
    </div>

    <button type="submit" class="btn btn-brand w-100 py-3">
        <i class="fas fa-calendar-check me-2"></i> Lanjutkan ke Pembayaran
    </button>
</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection