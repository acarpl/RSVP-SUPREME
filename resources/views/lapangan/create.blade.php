@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('partner.lapangan.index') }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="fw-bold text-brand mb-0">Tambah Lapangan</h1>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('partner.lapangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nama Lapangan -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Nama Lapangan <span class="text-danger">*</span></label>
                    <input type="text"
                           name="nama"
                           class="form-control form-control-lg @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}"
                           placeholder="Contoh: Futsal Arena Kelapa Gading"
                           required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Lokasi <span class="text-danger">*</span></label>
                    <input type="text"
                           name="lokasi"
                           class="form-control form-control-lg @error('lokasi') is-invalid @enderror"
                           value="{{ old('lokasi') }}"
                           placeholder="Contoh: Jl. Boulevard Raya No. 123"
                           required>
                    @error('lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Kapasitas (Orang) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                            <input type="number"
                                   name="kapasitas"
                                   class="form-control @error('kapasitas') is-invalid @enderror"
                                   value="{{ old('kapasitas') }}"
                                   min="1"
                                   max="1000"
                                   required>
                        </div>
                        @error('kapasitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Harga per Jam (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number"
                                   name="harga"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   value="{{ old('harga') }}"
                                   min="10000"
                                   step="1000"
                                   required>
                        </div>
                        @error('price_per_hour')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Gambar -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Foto Lapangan <span class="text-muted">(Opsional)</span></label>
                    <div class="border-2 border-dashed border-muted rounded-3 p-4 text-center">
                        <i class="fas fa-image fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-2">Unggah foto lapangan (JPG, PNG)</p>
                        <input type="file"
                               name="gambar"
                               class="form-control @error('gambar') is-invalid @enderror"
                               accept="image/*">
                        @error('gambar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted small">
                            Maks. 2MB • Resolusi minimal 800x600
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-grid gap-2 d-md-block">
                    <button type="submit" class="btn btn-brand px-5 py-2">
                        <i class="fas fa-save me-2"></i> Simpan Lapangan
                    </button>
                    <a href="{{ route('partner.lapangan.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
