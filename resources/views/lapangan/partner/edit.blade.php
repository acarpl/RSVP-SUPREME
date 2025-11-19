@extends('layouts.app')

@section('title', 'Edit Lapangan')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('partner.lapangan.index') }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="fw-bold text-brand mb-0">Edit: {{ $lapangan->nama }}</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('partner.lapangan.update', $lapangan) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <!-- Nama Lapangan -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Nama Lapangan <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control form-control-lg" 
                           value="{{ old('nama', $lapangan->nama) }}" 
                           placeholder="Contoh: Lapangan Surya Futsal"
                           required>
                    @error('nama')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="lokasi" class="form-control" 
                           value="{{ old('lokasi', $lapangan->lokasi) }}" 
                           placeholder="Contoh: Jl. Raya Bekasi No. 123"
                           required>
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
                                   value="{{ old('harga', $lapangan->harga) }}" 
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
                               value="{{ old('kapasitas', $lapangan->kapasitas) }}" 
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
                               value="aktif" {{ (old('status', $lapangan->status) == 'aktif') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_aktif">
                            <i class="fas fa-circle text-success me-2"></i> Aktif (Tampil ke Customer)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_nonaktif" 
                               value="nonaktif" {{ (old('status', $lapangan->status) == 'nonaktif') ? 'checked' : '' }}>
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
                    <div class="border-2 border-dashed rounded-3 p-4 text-center">
                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                        <h6 class="mb-2">Ganti Foto Lapangan</h6>
                        <p class="text-muted small mb-3">Format: JPG, PNG (max 2MB)</p>
                        <input type="file" name="gambar" id="gambar" class="form-control d-none" 
                               accept="image/*" onchange="previewImage(event)">
                        <button type="button" class="btn btn-outline-brand" onclick="document.getElementById('gambar').click()">
                            <i class="fas fa-upload me-2"></i>Pilih File Baru
                        </button>
                        
                        <!-- Preview Gambar Saat Ini -->
                        @if($lapangan->gambar)
                            <div class="mt-3">
                                <p class="mb-2">Foto saat ini:</p>
                                <img src="{{ asset('storage/' . $lapangan->gambar) }}" 
                                     alt="Foto {{ $lapangan->nama }}" 
                                     class="img-thumbnail rounded" style="max-height: 200px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="hapus_gambar" id="hapus_gambar" value="1">
                                    <label class="form-check-label" for="hapus_gambar">
                                        Hapus foto ini
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>
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
                        <i class="fas fa-save me-2"></i>Perbarui Lapangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const img = document.getElementById('previewImg');
    
    if (file) {
        if (!preview) {
            // Buat container preview jika belum ada
            const container = document.createElement('div');
            container.id = 'imagePreview';
            container.className = 'mt-3';
            
            const imgPreview = document.createElement('img');
            imgPreview.id = 'previewImg';
            imgPreview.className = 'img-thumbnail rounded';
            imgPreview.style.maxHeight = '200px';
            
            container.appendChild(imgPreview);
            event.target.parentElement.appendChild(container);
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection