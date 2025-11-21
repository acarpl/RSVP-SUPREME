@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('partner.products.manage') }}" class="btn btn-outline-brand rounded-circle me-3" title="Kembali">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="fw-bold text-brand mb-0">Edit Produk</h1>
                    <p class="text-muted mb-0">Perbarui informasi produk <strong>{{ $product->name }}</strong>.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('partner.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Kolom Kiri: Info Dasar -->
                            <div class="col-md-7">
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="name"
                                           value="{{ old('name', $product->name) }}"
                                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           placeholder="Contoh: Jersey Tim, Bola Futsal Pro"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                                    <select name="category"
                                            class="form-select form-select-lg @error('category') is-invalid @enderror"
                                            required>
                                        <option value="">— Pilih kategori —</option>
                                        <option value="alat" {{ old('category', $product->category) == 'alat' ? 'selected' : '' }}>
                                            <i class="fas fa-futbol me-2"></i> Alat Olahraga
                                        </option>
                                        <option value="makanan" {{ old('category', $product->category) == 'makanan' ? 'selected' : '' }}>
                                            <i class="fas fa-utensils me-2"></i> Makanan & Minuman
                                        </option>
                                        <option value="merchandise" {{ old('category', $product->category) == 'merchandise' ? 'selected' : '' }}>
                                            <i class="fas fa-tshirt me-2"></i> Merchandise
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Harga (Rp) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number"
                                                   name="price"
                                                   value="{{ old('price', $product->price) }}"
                                                   class="form-control @error('price') is-invalid @enderror"
                                                   placeholder="0"
                                                   min="1000"
                                                   required>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Stok <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number"
                                                   name="stock"
                                                   value="{{ old('stock', $product->stock) }}"
                                                   class="form-control @error('stock') is-invalid @enderror"
                                                   placeholder="0"
                                                   min="0"
                                                   required>
                                            <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                        </div>
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-medium">Deskripsi</label>
                                    <textarea name="description"
                                              class="form-control"
                                              rows="3"
                                              placeholder="Contoh: Bahan polyester, ukuran S/M/L, cocok untuk latihan & pertandingan">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Preview Foto -->
                            <div class="col-md-5">
                                <div class="text-center mb-4">
                                    <div class="position-relative d-inline-block">
                                        <img id="imagePreview"
                                             src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/300x300/e2e8f0/94a3b8?text=+' }}"
                                             alt="Foto {{ $product->name }}"
                                             class="img-fluid rounded-3 shadow-sm"
                                             style="width: 100%; max-width: 300px; height: 300px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-black bg-opacity-50 rounded-3 opacity-0 hover:opacity-100 transition-opacity">
                                            <span class="text-white text-center px-3">
                                                <i class="fas fa-camera fa-2x mb-2"></i><br>
                                                Klik untuk ganti foto
                                            </span>
                                        </div>
                                    </div>
                                    @if($product->image)
                                        <small class="text-muted d-block mt-2">Foto saat ini</small>
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-medium">Ganti Foto (Opsional)</label>
                                    <div class="input-group">
                                        <input type="file"
                                               name="image"
                                               id="imageUpload"
                                               class="form-control @error('image') is-invalid @enderror"
                                               accept="image/*">
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted small">
                                        Kosongkan jika tidak ingin mengganti foto
                                    </div>
                                </div>

                                <div class="alert alert-light border-0 rounded-3 p-3 mb-0">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle text-brand mt-1 me-2"></i>
                                        <small class="text-muted">
                                            Foto produk yang jelas akan meningkatkan minat pembeli.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-brand px-5">
                                <i class="fas fa-sync-alt me-2"></i> Perbarui Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageUpload = document.getElementById('imageUpload');
    const imagePreview = document.getElementById('imagePreview');

    if (imageUpload && imagePreview) {
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
@endsection