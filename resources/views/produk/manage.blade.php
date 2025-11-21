@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <a href="{{ route('partner.dashboard') }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="fw-bold text-brand mb-1">
                <i class="fas fa-store me-2"></i> Kelola Produk
            </h1>
        </div>
        <a href="{{ route('partner.products.create') }}" class="btn btn-brand px-4 py-2 mt-3 mt-md-0">
            <i class="fas fa-plus me-1"></i> Tambah Produk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Daftar Produk -->
    @if($products->count())
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                        <!-- Gambar Produk -->
                        <div style="height: 200px; background: #f1f5f9;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-100 h-100"
                                     style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-box-open fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Konten -->
                        <div class="card-body d-flex flex-column p-3">
                            <!-- Badge Kategori -->
                            <span class="badge 
                                @if($product->category == 'alat') bg-primary text-white
                                @elseif($product->category == 'makanan') bg-success text-white
                                @else bg-warning text-dark @endif 
                                fw-normal mb-2">
                                {{ ucfirst($product->category) }}
                            </span>

                            <h5 class="card-title fw-bold mb-2">{{ Str::limit($product->name, 25) }}</h5>
                            
                            <div class="mb-2">
                                <p class="card-text text-muted small mb-1">
                                    {{ Str::limit($product->description, 50) }}
                                </p>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 fw-bold text-primary mb-0">
                                        Rp {{ number_format($product->price) }}
                                    </span>
                                    <span class="badge 
                                        @if($product->stock > 5) bg-success text-white
                                        @elseif($product->stock > 0) bg-warning text-dark
                                        @else bg-danger text-white @endif">
                                        {{ $product->stock }} stok
                                    </span>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="d-grid gap-2">
                                    <a href="{{ route('partner.products.edit', $product->id) }}" 
                                       class="btn btn-outline-brand">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    
                                    <form method="POST" 
                                          action="{{ route('partner.products.destroy', $product->id) }}"
                                          class="d-grid">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-outline-danger"
                                                onclick="return confirm('Yakin hapus {{ addslashes($product->name) }}?')">
                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                 style="width: 100px; height: 100px;">
                <i class="fas fa-box-open fa-3x text-muted"></i>
            </div>
            
            <h2 class="fw-bold text-muted mb-3">Belum Ada Produk</h2>
            <p class="text-muted mb-4 px-3">
                Tambahkan produk pertama untuk venue-mu dan tingkatkan pengalaman booking pelanggan!
            </p>
            
            <a href="{{ route('partner.products.create') }}" class="btn btn-brand px-4 py-2">
                <i class="fas fa-plus me-1"></i> Tambah Produk Pertama
            </a>
        </div>
    @endif

    <!-- Tips untuk Mitra -->
    <div class="alert alert-info border-0 rounded-3 mt-4">
        <div class="d-flex">
            <div class="flex-shrink-0 me-3 mt-1">
                <i class="fas fa-lightbulb text-info fa-lg"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-2">Tips Kelola Produk</h5>
                <ul class="mb-0 small">
                    <li>Upload foto produk berkualitas tinggi</li>
                    <li>Atur stok secara berkala</li>
                    <li>Tambahkan deskripsi detail untuk meningkatkan minat</li>
                    <li>Kelompokkan produk ke kategori yang sesuai</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection