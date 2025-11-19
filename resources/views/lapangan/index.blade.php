@extends('layouts.app')

@section('title', 'Daftar Lapangan')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-brand display-5">Booking Lapangan Instan</h1>
        <p class="text-muted lead">
            <i class="fas fa-futbol me-1"></i> 
            Temukan, pesan, dan main dalam 60 detik!
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-bold mb-0">
                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                {{ $lapangans->total() }} Lapangan Tersedia
            </h2>
        </div>
        <div>
            <form method="GET" class="d-flex">
                <input type="text" 
                       name="search" 
                       class="form-control form-control-sm me-2"
                       placeholder="Cari lapangan..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-brand btn-sm">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Grid Lapangan -->
    @if($lapangans->count())
        <div class="row g-4">
            @foreach($lapangans as $lapangan)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                        <!-- Gambar Lapangan -->
                        <div style="height: 220px; background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('https://placehold.co/600x400/e2e8f0/94a3b8?text=Lapangan') center/cover no-repeat;">
                            @if($lapangan->gambar)
                                <img src="{{ asset('storage/' . $lapangan->gambar) }}"
                                     alt="{{ $lapangan->nama }}"
                                     class="w-100 h-100"
                                     style="object-fit: cover;">
                            @endif
                            
                            <!-- Badge Status -->
                            <span class="position-absolute top-0 start-0 bg-success text-white fw-bold px-2 py-1 small">
                                <i class="fas fa-check-circle me-1"></i> Tersedia
                            </span>

                            <!-- Harga -->
                            <div class="position-absolute bottom-0 start-0 w-100 bg-white bg-opacity-90 p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary fs-5">
                                        Rp {{ number_format($lapangan->harga) }}
                                    </span>
                                    <small class="text-muted">/ jam</small>
                                </div>
                            </div>
                        </div>

                        <!-- Konten -->
                        <div class="card-body d-flex flex-column p-3">
                            <h5 class="card-title fw-bold mb-2">{{ $lapangan->nama }}</h5>
                            
                            <div class="mb-2">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <span>{{ Str::limit($lapangan->lokasi, 30) }}</span>
                                </div>
                                <div class="d-flex align-items-center text-muted small mt-1">
                                    <i class="fas fa-users me-1"></i>
                                    <span>{{ $lapangan->kapasitas }} orang</span>
                                </div>
                            </div>

                            <!-- Fitur -->
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                @if($lapangan->fitur_kamar_ganti ?? false)
                                    <span class="badge bg-light text-dark">Kamar Ganti</span>
                                @endif
                                @if($lapangan->fitur_parkir ?? false)
                                    <span class="badge bg-light text-dark">Parkir Luas</span>
                                @endif
                                @if($lapangan->fitur_wifi ?? false)
                                    <span class="badge bg-light text-dark">WiFi</span>
                                @endif
                            </div>

                            <!-- Tombol Booking -->
                            <div class="mt-auto">
                                <a href="{{ route('booking.order-now', $lapangan->id) }}" 
                                   class="btn btn-brand w-100 py-2">
                                    <i class="fas fa-bolt me-2"></i> Order Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $lapangans->links() }}
        </div>

    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                 style="width: 100px; height: 100px;">
                <i class="fas fa-futbol fa-3x text-muted"></i>
            </div>
            
            <h2 class="fw-bold text-muted mb-3">Belum Ada Lapangan Tersedia</h2>
            <p class="text-muted mb-4 px-3">
                Lapangan sedang dalam pemeliharaan atau belum tersedia di wilayah Anda.
            </p>
            
            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                <a href="{{ route('home') }}" class="btn btn-outline-brand px-4 py-2">
                    <i class="fas fa-home me-1"></i> Kembali ke Beranda
                </a>
                <a href="https://wa.me/628123456789" class="btn btn-success px-4 py-2">
                    <i class="fab fa-whatsapp me-1"></i> Hubungi Kami
                </a>
            </div>
        </div>
    @endif

    <!-- Tips -->
    <div class="alert alert-light border-0 rounded-3 mt-5">
        <div class="d-flex">
            <div class="flex-shrink-0 me-3 mt-1">
                <i class="fas fa-lightbulb text-brand fa-lg"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-2">Tips Booking Lapangan</h5>
                <ul class="mb-0 small text-muted">
                    <li>Booking minimal 1 jam sebelum jadwal</li>
                    <li>Pilih durasi 2+ jam untuk diskon 10%</li>
                    <li>Tambahkan produk (minuman, bola) di halaman booking</li>
                    <li>Gratis antar-jemput untuk booking > 3 jam</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection