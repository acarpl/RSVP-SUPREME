@extends('layouts.app')

@section('title', 'Tambah Lapangan')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('partner.lapangan.index') }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="fw-bold text-brand mb-0">Tambah Lapangan Baru</h1>
            <p class="text-muted mb-0">Lengkapi informasi lapangan untuk ditampilkan ke customer</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- ✅ Pastikan action dan method benar -->
            <form action="{{ route('partner.lapangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nama Lapangan -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Nama Lapangan <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control form-control-lg" 
                           value="{{ old('nama') }}" 
                           placeholder="Contoh: Lapangan Surya Futsal"
                           required>
                    @error('nama')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Lokasi - ✅ WAJIB DIPERHATIKAN -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="lokasi" class="form-control" 
                           value="{{ old('lokasi') }}" 
                           placeholder="Contoh: Jl. Raya Bekasi No. 123"
                           required> <!-- ✅ required, TANPA disabled -->
                    @error('lokasi')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <!-- Harga -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Harga per Jam (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" class="form-control" 
                                   value="{{ old('harga', 0) }}" 
                                   min="0" required>
                        </div>
                        @error('harga')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Kapasitas (Orang)</label>
                        <input type="number" name="kapasitas" class="form-control" 
                               value="{{ old('kapasitas') }}" 
                               min="0">
                        @error('kapasitas')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_aktif" 
                               value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_aktif">
                            <i class="fas fa-circle text-success me-2"></i> Aktif (Tampil ke Customer)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_nonaktif" 
                               value="nonaktif" {{ old('status', 'aktif') == 'nonaktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_nonaktif">
                            <i class="fas fa-circle text-secondary me-2"></i> Nonaktif (Sembunyikan dari Customer)
                        </label>
                    </div>
                    @error('status')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Upload Gambar -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Foto Lapangan</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    @error('gambar')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex gap-3">
                    <a href="{{ route('partner.lapangan.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-brand px-4">
                        <i class="fas fa-save me-2"></i>Simpan Lapangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ✅ Debug: Cek apakah form mengirim data -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const lokasi = document.querySelector('input[name="lokasi"]').value.trim();
        console.log('Data yang akan dikirim:', {
            nama: document.querySelector('input[name="nama"]').value,
            lokasi: lokasi,
            harga: document.querySelector('input[name="harga"]').value,
            kapasitas: document.querySelector('input[name="kapasitas"]').value,
            status: document.querySelector('input[name="status"]:checked')?.value
        });
        
        if (!lokasi) {
            e.preventDefault();
            alert('⚠️ Lokasi wajib diisi!');
            document.querySelector('input[name="lokasi"]').focus();
        }
    });
});
</script>
@endpush
@endsection