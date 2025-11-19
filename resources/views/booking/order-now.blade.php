@extends('layouts.app')

@section('title', 'Booking - ' . $lapangan->nama)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Booking Lapangan</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3>{{ $lapangan->nama }}</h3>
                        <p class="text-muted">{{ $lapangan->lokasi }}</p>
                        <div class="alert alert-info">
                            Harga: <strong>Rp {{ number_format($lapangan->harga, 0, ',', '.') }}/jam</strong>
                        </div>
                    </div>

                    <form action="{{ route('booking.order-now.store', $lapangan) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tanggal Booking</label>
                            <input type="date" name="tanggal" class="form-control" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('tanggal')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="row mb-3">
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
                                @error('jam_mulai')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Durasi</label>
                                <select name="durasi" class="form-select" required>
                                    <option value="1" {{ old('durasi') == 1 ? 'selected' : '' }}>1 jam</option>
                                    <option value="2" {{ old('durasi') == 2 ? 'selected' : '' }}>2 jam</option>
                                    <option value="3" {{ old('durasi') == 3 ? 'selected' : '' }}>3 jam</option>
                                    <option value="4" {{ old('durasi') == 4 ? 'selected' : '' }}>4 jam</option>
                                </select>
                                @error('durasi')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
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