@extends('layouts.app')

@section('title', 'Booking - ' . $lapangan->nama)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-brand rounded-circle me-3" title="Kembali">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="fw-bold text-brand mb-0">Booking Lapangan</h1>
                    <p class="text-muted mb-0">{{ $lapangan->nama }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <!-- Gambar Lapangan -->
                <div style="height: 200px; background: #f1f5f9;">
                    @if($lapangan->gambar)
                        <img src="{{ asset('storage/' . $lapangan->gambar) }}"
                             alt="{{ $lapangan->nama }}"
                             class="w-100 h-100"
                             style="object-fit: cover;">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-futbol fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>

                <!-- Konten -->
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">{{ $lapangan->nama }}</h3>
                        <p class="text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $lapangan->lokasi }}
                        </p>
                        <div class="bg-light rounded-2 p-3">
                            <div class="d-flex justify-content-center align-items-baseline">
                                <span class="fw-bold text-primary fs-2">Rp {{ number_format($lapangan->harga, 0, ',', '.') }}</span>
                                <span class="text-muted ms-2">/ jam</span>
                            </div>
                        </div>
                    </div>

                    {{-- ✅ TAMPILKAN ERROR SECARA EKSPLISIT --}}
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-2 mb-4">
                            <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-circle me-1"></i> Mohon perbaiki data berikut:</h6>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ✅ PERBAIKAN UTAMA: ganti route dari 'payment.create' → 'payment.store' --}}
                    <form action="{{ route('payment.store', $lapangan->id) }}" method="POST">
                        @csrf

                        <!-- Tanggal & Jam -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Jadwal Booking</h5>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           name="tanggal" 
                                           class="form-control form-control-lg @error('tanggal') is-invalid @enderror"
                                           value="{{ old('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                           min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                           required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Jam Mulai <span class="text-danger">*</span></label>
                                    <select name="jam_mulai" 
                                            class="form-select form-select-lg @error('jam_mulai') is-invalid @enderror"
                                            required>
                                        <option value="">Pilih jam</option>
                                        @for ($h = 8; $h <= 21; $h++)
                                            @for ($m = 0; $m < 60; $m += 30)
                                                @php $time = sprintf('%02d:%02d', $h, $m); @endphp
                                                <option value="{{ $time }}" 
                                                        {{ old('jam_mulai', '18:00') == $time ? 'selected' : '' }}>
                                                    {{ $h }}.{{ $m == 0 ? '00' : $m }}
                                                </option>
                                            @endfor
                                        @endfor
                                    </select>
                                    @error('jam_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Durasi <span class="text-danger">*</span></label>
                                    <select name="durasi" 
                                            class="form-select form-select-lg @error('durasi') is-invalid @enderror"
                                            required>
                                        <option value="">Pilih durasi</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ old('durasi', 2) == $i ? 'selected' : '' }}>
                                                {{ $i }} jam
                                            </option>
                                        @endfor
                                    </select>
                                    @error('durasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Ringkasan -->
                        <div class="border-top pt-3">
                            <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Harga per jam</span>
                                <span class="fw-bold">Rp {{ number_format($lapangan->harga, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Durasi</span>
                                <span id="durasiText">{{ old('durasi', 2) }} jam</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total:</span>
                                <span id="totalHarga">
                                    Rp {{ number_format($lapangan->harga * (old('durasi', 2)), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-brand w-100 py-3 fs-5">
                                <i class="fas fa-credit-card me-2"></i> Lanjut ke Pembayaran
                            </button>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i> 
                                    Pembayaran aman via Midtrans • Bisa dibatalkan dalam 1 jam
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips -->
            <div class="alert alert-light border-0 rounded-3 mt-4">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-lightbulb text-brand me-1"></i> 
                    Tips Booking
                </h6>
                <ul class="mb-0 small text-muted">
                    <li>Booking minimal 1 jam sebelum jadwal</li>
                    <li>Pilih durasi 3+ jam untuk diskon 10%</li>
                    <li>Tambahkan produk (minuman, bola) di keranjang</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hargaPerJam = {{ $lapangan->harga }};
    const durasiSelect = document.querySelector('[name="durasi"]');
    const durasiText = document.getElementById('durasiText');
    const totalHarga = document.getElementById('totalHarga');

    function updateTotal() {
        const durasi = parseInt(durasiSelect.value) || 2;
        const total = hargaPerJam * durasi;
        
        durasiText.textContent = durasi + ' jam';
        totalHarga.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    durasiSelect.addEventListener('change', updateTotal);
    updateTotal(); // Inisialisasi
});
</script>
@endpush
@endsection