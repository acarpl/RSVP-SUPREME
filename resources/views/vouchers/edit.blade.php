@extends('layouts.app')

@section('title', 'Edit Voucher')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('partner.vouchers.index') }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="fw-bold text-brand">Edit Voucher</h1>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">
            <form method="POST" action="{{ route('partner.vouchers.update', $voucher) }}" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Voucher -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Nama Voucher <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $voucher->name) }}" required>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kode Voucher -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Kode Voucher <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $voucher->code) }}" required>
                    <div class="form-text">Akan otomatis diubah menjadi huruf kapital</div>
                    @error('code')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Gambar Saat Ini -->
                @if($voucher->image)
                    <div class="mb-3">
                        <label class="form-label fw-medium">Gambar Saat Ini</label>
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $voucher->image) }}" 
                                 alt="{{ $voucher->name }}"
                                 class="img-thumbnail" 
                                 style="max-height: 150px; object-fit: cover;">
                        </div>
                    </div>
                @endif

                <!-- Ganti Gambar -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Ganti Gambar (Opsional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="form-text">Format: JPG, JPEG, PNG (Max: 2MB)</div>
                    @error('image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $voucher->description) }}</textarea>
                    @error('description')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipe & Nilai Diskon -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Tipe Diskon <span class="text-danger">*</span></label>
                        <select name="discount_type" class="form-select" required>
                            <option value="">Pilih tipe</option>
                            <option value="percentage" {{ (old('discount_type', $voucher->discount_type) == 'percentage') ? 'selected' : '' }}>
                                Persentase (%)
                            </option>
                            <option value="fixed" {{ (old('discount_type', $voucher->discount_type) == 'fixed') ? 'selected' : '' }}>
                                Nominal Tetap
                            </option>
                        </select>
                        @error('discount_type')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Nilai Diskon <span class="text-danger">*</span></label>
                        <input type="number" name="discount_value" class="form-control" 
                               value="{{ old('discount_value', $voucher->discount_value) }}" required min="0">
                        @error('discount_value')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Min Transaksi -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Min. Transaksi (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="min_amount" class="form-control" 
                           value="{{ old('min_amount', $voucher->min_amount) }}" required min="0">
                    @error('min_amount')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Masa Berlaku -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Berlaku Dari <span class="text-danger">*</span></label>
                        <input type="date" name="valid_from" class="form-control" 
                               value="{{ old('valid_from', $voucher->valid_from?->format('Y-m-d')) }}" required>
                        @error('valid_from')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Berlaku Sampai <span class="text-danger">*</span></label>
                        <input type="date" name="valid_until" class="form-control" 
                               value="{{ old('valid_until', $voucher->valid_until?->format('Y-m-d')) }}" required>
                        @error('valid_until')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('partner.vouchers.index') }}" class="btn btn-outline-brand flex-fill">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-brand flex-fill">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection