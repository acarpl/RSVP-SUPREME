@extends('layouts.app')

@section('title', 'Checkout Keranjang')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('cart.index') }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="fw-bold text-brand mb-0">Checkout Keranjang</h1>
    </div>

    <div class="row g-4">
        <!-- Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-cart me-2"></i> Jenis Pesanan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf

                        <!-- Opsi: Beli / Sewa -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Jenis Pesanan</label>
                            <div class="form-check mb-2">
                                <input type="radio" name="jenis_pesanan" id="beli_produk" value="beli_produk" class="form-check-input" checked>
                                <label class="form-check-label" for="beli_produk">
                                    <strong>Beli Produk</strong> (Kirim ke alamat_pengiriman Anda pada form alamat_pengiriman atau tulis diambil di lapangan pada form alamat_pengiriman)
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="jenis_pesanan" id="sewa_alat" value="sewa_alat" class="form-check-input">
                                <label class="form-check-label" for="sewa_alat">
                                    <strong>Sewa Alat</strong> (Pakai di lapangan yang sudah dibooking)
                                </label>
                            </div>
                        </div>

                        <!-- Alamat (hanya muncul jika beli_produk) -->
                        <div id="alamat_pengiriman_section">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Alamat Pengiriman <span class="text-danger">*</span></label>
                                <textarea name="alamat_pengiriman"
                                    class="form-control @error('alamat_pengiriman') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Contoh: Jl. Sudirman No. 123, Jakarta Pusat"
                                    required>{{ old('alamat_pengiriman') }}</textarea>
                                @error('alamat_pengiriman')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Jadwal (hanya muncul jika sewa_alat) -->
                        <div id="jadwal_section" style="display: none;">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date"
                                        name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
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
                                        class="form-select @error('jam_mulai') is-invalid @enderror"
                                        required>
                                        <option value="">Pilih jam</option>
                                        @for ($h = 8; $h <= 21; $h++)
                                            @for ($m=0; $m < 60; $m +=30)
                                            @php $time=sprintf('%02d:%02d', $h, $m); @endphp
                                            <option value="{{ $time }}" {{ old('jam_mulai') == $time ? 'selected' : '' }}>
                                            {{ $h }}:{{ $m == 0 ? '00' : $m }}
                                            </option>
                                            @endfor
                                            @endfor
                                    </select>
                                    @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium">Durasi <span class="text-danger">*</span></label>
                                <select name="durasi"
                                    class="form-select form-select-lg @error('durasi') is-invalid @enderror"
                                    required>
                                    <option value="">Pilih durasi</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('durasi') == $i ? 'selected' : '' }}>
                                        {{ $i }} jam
                                        </option>
                                        @endfor
                                </select>
                                @error('durasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 py-3">
                            <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-receipt me-2"></i> Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Produk dari Keranjang ({{ count($items) }})</h6>
                    @foreach($items as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item['quantity'] }}x {{ $item['product']->name }}</span>
                        <span>Rp {{ number_format($item['subtotal']) }}</span>
                    </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total:</span>
                        <span class="text-primary">Rp {{ number_format($total) }}</span>
                    </div>

                    <div class="alert alert-light small mt-3 p-2">
                        <i class="fas fa-shield-alt text-success me-1"></i>
                        Pembayaran aman via Midtrans
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisPesanan = document.querySelectorAll('input[name="jenis_pesanan"]');
    const alamat_pengirimanField = document.querySelector('textarea[name="alamat_pengiriman"]');
    const tanggalField = document.querySelector('input[name="tanggal"]');
    const jamField = document.querySelector('select[name="jam_mulai"]');
    const durasiField = document.querySelector('select[name="durasi"]');

    function updateValidation() {
        const isBeli = document.getElementById('beli_produk').checked;
        
        // Atur required & disabled dinamis
        if (isBeli) {
            // Beli Produk: alamat_pengiriman wajib, jadwal nonaktif
            alamat_pengirimanField.setAttribute('required', 'required');
            alamat_pengirimanField.disabled = false;
            [tanggalField, jamField, durasiField].forEach(el => {
                el.removeAttribute('required');
                el.disabled = true;
            });
        } else {
            // Sewa Alat: jadwal wajib, alamat_pengiriman nonaktif
            alamat_pengirimanField.removeAttribute('required');
            alamat_pengirimanField.disabled = true;
            [tanggalField, jamField, durasiField].forEach(el => {
                el.setAttribute('required', 'required');
                el.disabled = false;
            });
        }
    }

    // Trigger saat ganti opsi
    jenisPesanan.forEach(radio => {
        radio.addEventListener('change', updateValidation);
    });

    // Inisialisasi awal
    updateValidation();
});
</script>
@endpush
@endsection