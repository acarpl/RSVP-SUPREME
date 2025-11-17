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
                    <p class="text-muted mb-0">{{ count($products) }} produk siap dibooking</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('booking.store-from-cart') }}">
                        @csrf

                        <!-- Pilih Lapangan -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Pilih Lapangan</label>
                            <select name="lapangan_id" class="form-select form-select-lg" required>
                                <option value="">Pilih lapangan</option>
                                @foreach($lapangans as $lapangan)
                                    <option value="{{ $lapangan->id }}">
                                        {{ $lapangan->nama }} — Rp {{ number_format($lapangan->price_per_hour) }}/jam
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Waktu Booking -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Jadwal Booking</label>
                            <div class="row g-3">
                                <div class="col-12">
                                    <input type="datetime-local" 
                                           name="start_time" 
                                           class="form-control form-control-lg"
                                           required
                                           min="{{ \Carbon\Carbon::now()->addHour()->format('Y-m-d\TH:i') }}">
                                </div>
                                <div class="col-12">
                                    <select name="duration_hours" class="form-select form-select-lg" required>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $i }} jam</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Ringkasan Keranjang -->
                        <div class="border-top pt-3">
                            <h5 class="mb-3">Produk dari Keranjang</h5>
                            @foreach($products as $product)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $product['name'] }} × {{ $product['quantity'] }}</span>
                                    <span>Rp {{ number_format($product['price'] * $product['quantity']) }}</span>
                                </div>
                            @endforeach
                            
                            <input type="hidden" name="from_cart" value="1">
                        </div>

                        <button type="submit" class="btn btn-brand w-100 py-3">
                            <i class="fas fa-shopping-cart me-2"></i> Konfirmasi Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection