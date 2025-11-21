@extends('layouts.app')

@section('title', 'Gabung Jadi Mitra')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Header -->
            <div class="text-center mb-5">
                <div class="bg-brand text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 70px; height: 70px;">
                    <i class="fas fa-handshake fa-2x"></i>
                </div>
                <h1 class="fw-bold text-brand mb-2">Gabung Jadi Mitra Sportykuy</h1>
                <p class="text-muted">
                    Jadilah bagian dari komunitas olahraga terbesar di Jabodetabek
                </p>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-4">
                    <!-- Info Card -->
                    <div class="alert alert-light border-0 rounded-3 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <i class="fas fa-info-circle text-brand fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-2">Apa Keuntungan Jadi Mitra?</h6>
                                <ul class="mb-0 small text-muted">
                                    <li>✓ Dapatkan pelanggan baru setiap hari</li>
                                    <li>✓ Dashboard analitik untuk pantau performa</li>
                                    <li>✓ Pembayaran aman & harian</li>
                                    <li>✓ Tim support 24/7</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> 
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Perhatian!</strong> Ada data yang perlu diperbaiki:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('partner.register') }}" method="POST">
                        @csrf

                        <!-- Nama Usaha -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Nama Usaha / Lapangan <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-store text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="nama_usaha" 
                                       class="form-control @error('nama_usaha') is-invalid @enderror"
                                       placeholder="Contoh: Futsal Arena Kelapa Gading"
                                       value="{{ old('nama_usaha') }}"
                                       required>
                            </div>
                            @error('nama_usaha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Alamat Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                </span>
                                <textarea name="alamat_usaha" 
                                          class="form-control @error('alamat_usaha') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Contoh: Jl. Boulevard Raya No. 123, Kelapa Gading, Jakarta Utara"
                                          required>{{ old('alamat_usaha') }}</textarea>
                            </div>
                            @error('alamat_usaha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Nomor Telepon <span class="text-muted">(Opsional)</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-phone text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="telepon" 
                                       class="form-control"
                                       placeholder="0812-3456-7890"
                                       value="{{ old('telepon') }}">
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-brand btn-lg py-3">
                                <i class="fas fa-paper-plane me-2"></i> 
                                Ajukan Menjadi Mitra
                            </button>
                            
                            <a href="{{ route('home') }}" class="btn btn-outline-brand">
                                <i class="fas fa-arrow-left me-1"></i> 
                                Kembali ke Beranda
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips -->
            <div class="alert alert-light border-0 rounded-3 mt-4">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-lightbulb text-brand me-1"></i> 
                    Tips Pengajuan Mitra
                </h6>
                <ul class="mb-0 small text-muted">
                    <li>Gunakan nama usaha yang sesuai dengan lokasi fisik</li>
                    <li>Lengkapi alamat dengan kode pos untuk akurasi maps</li>
                    <li>Pastikan nomor telepon aktif untuk verifikasi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection