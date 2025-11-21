@extends('layouts.app')

@section('title', 'Tambah Lapangan')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('partner.lapangan.index') }}" class="btn btn-outline-brand rounded-circle me-3" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="fw-bold text-brand mb-0">Tambah Lapangan</h1>
            <p class="text-muted mb-0">Lengkapi data lapangan baru untuk venue-mu</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-4">
                    <form action="{{ route('partner.lapangan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nama & Lokasi -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nama Lapangan <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="nama"
                                       class="form-control form-control-lg @error('nama') is-invalid @enderror"
                                       value="{{ old('nama') }}"
                                       placeholder="Contoh: Futsal Arena"
                                       required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Lokasi <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="lokasi"
                                       class="form-control form-control-lg @error('lokasi') is-invalid @enderror"
                                       value="{{ old('lokasi') }}"
                                       placeholder="Jakarta, Bekasi, dll"
                                       required>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kapasitas & Harga -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Kapasitas <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <input type="number"
                                           name="kapasitas"
                                           class="form-control @error('kapasitas') is-invalid @enderror"
                                           value="{{ old('kapasitas', 12) }}"
                                           min="2"
                                           max="100"
                                           required>
                                    <span class="input-group-text">orang</span>
                                </div>
                                @error('kapasitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Harga per Jam <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number"
                                           name="harga"
                                           class="form-control @error('harga') is-invalid @enderror"
                                           value="{{ old('harga', 250000) }}"
                                           min="10000"
                                           step="5000"
                                           required>
                                    <span class="input-group-text">/jam</span>
                                </div>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusAktif" value="aktif" 
                                           {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="statusAktif">
                                        <span class="badge bg-success text-white"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                        <small class="text-muted d-block">Bisa dipesan pelanggan</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusNonaktif" value="nonaktif"
                                           {{ old('status') == 'nonaktif' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusNonaktif">
                                        <span class="badge bg-secondary text-white"><i class="fas fa-pause-circle me-1"></i> Nonaktif</span>
                                        <small class="text-muted d-block">Sementara tidak tersedia</small>
                                    </label>
                                </div>
                            </div>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gambar -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Foto Lapangan</label>
                            <div class="border-2 border-dashed border-muted rounded-3 p-4 text-center">
                                <i class="fas fa-futbol fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-2">Seret & lepas atau klik untuk unggah</p>
                                <p class="small text-muted mb-3">Format: JPG, PNG • Max: 2MB</p>
                                
                                <input type="file"
                                       name="gambar"
                                       id="gambarInput"
                                       class="form-control d-none"
                                       accept="image/*">
                                <label for="gambarInput" class="btn btn-outline-brand">
                                    <i class="fas fa-upload me-1"></i> Pilih File
                                </label>

                                <!-- Preview Gambar -->
                                <div id="previewContainer" class="mt-3 d-none">
                                    <img id="previewImage" 
                                         src="#" 
                                         alt="Preview"
                                         class="img-fluid rounded border"
                                         style="max-height: 200px; object-fit: cover;">
                                    <button type="button" 
                                            id="removePreview" 
                                            class="btn btn-sm btn-outline-danger mt-2">
                                        <i class="fas fa-times me-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            @error('gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('partner.lapangan.index') }}" 
                               class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-brand px-5 py-2">
                                <i class="fas fa-save me-2"></i> Simpan Lapangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips -->
            <div class="alert alert-light border-0 rounded-3 mt-4">
                <h6 class="fw-bold mb-2"><i class="fas fa-lightbulb text-brand me-1"></i> Tips Membuat Lapangan</h6>
                <ul class="mb-0 small text-muted">
                    <li>Gunakan nama yang mudah diingat (contoh: "Futsal Arena Kelapa Gading")</li>
                    <li>Upload foto lapangan dari beberapa sudut</li>
                    <li>Atur status ke "Nonaktif" saat sedang maintenance</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gambarInput = document.getElementById('gambarInput');
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');
    const removePreview = document.getElementById('removePreview');

    // Preview gambar
    gambarInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.src = event.target.result;
                previewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Hapus preview
    removePreview.addEventListener('click', function() {
        previewContainer.classList.add('d-none');
        gambarInput.value = '';
    });
});
</script>
@endpush
@endsection