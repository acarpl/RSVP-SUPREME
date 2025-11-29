@extends('layouts.app')

@section('title', 'Daftar Lapangan')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-brand display-5">Booking Lapangan Instan</h1>
        <p class="text-muted lead">
            <i class="fas fa-futbol me-1"></i> Temukan, pesan, dan main dalam 60 detik!
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Form (Updated) -->
    <div class="mb-4 p-3 bg-white rounded shadow-sm">
        <form method="GET" action="{{ route('lapangan.index') }}" class="row g-3 align-items-end">
            {{-- Pencarian Umum --}}
            <div class="col-md-4">
                <label class="form-label fw-bold"><i class="fas fa-search me-1"></i> Cari Nama/Lokasi</label>
                <input type="text" name="search" class="form-control" 
                       value="{{ request('search') }}"
                       placeholder="Contoh: Futsal, Gading...">
            </div>

            {{-- Filter Kota --}}
            <div class="col-md-3">
                <label class="form-label fw-bold"><i class="fas fa-map-marker-alt me-1"></i> Kota</label>
                <input type="text" name="kota" class="form-control" 
                       value="{{ request('kota') }}"
                       placeholder="Contoh: Jakarta, Bekasi">
            </div>

            {{-- Filter Tipe --}}
            <div class="col-md-3">
                <label class="form-label fw-bold"><i class="fas fa-futbol me-1"></i> Tipe Lapangan</label>
                <select name="tipe" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="futsal"     {{ request('tipe') == 'futsal'     ? 'selected' : '' }}>Futsal</option>
                    <option value="badminton"  {{ request('tipe') == 'badminton'  ? 'selected' : '' }}>Badminton</option>
                    <option value="basket"     {{ request('tipe') == 'basket'     ? 'selected' : '' }}>Basket</option>
                    <option value="tenis"      {{ request('tipe') == 'tenis'      ? 'selected' : '' }}>Tenis</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-brand w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'kota', 'tipe']))
                    <a href="{{ route('lapangan.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync me-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grid Lapangan -->
    @if($lapangans->count())
        <div class="row g-4">
            @foreach($lapangans as $lapangan)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 hover-shadow">
                        <!-- Gambar -->
                        <div class="position-relative" style="height: 200px;">
                            @if($lapangan->gambar)
                                <img src="{{ asset('storage/' . $lapangan->gambar) }}"
                                     alt="{{ $lapangan->nama }}"
                                     class="w-100 h-100"
                                     style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-futbol fa-3x text-secondary"></i>
                                </div>
                            @endif

                            <!-- Badge Partner -->
                            @if($lapangan->partner)
                                <span class="position-absolute top-0 end-0 bg-brand text-white fw-bold px-2 py-1 small z-1"
                                      title="Dikelola oleh {{ $lapangan->partner->name }}">
                                    <i class="fas fa-building me-1"></i> {{ Str::limit($lapangan->partner->name, 12) }}
                                </span>
                            @endif

                            <!-- Badge Status -->
                            <span class="position-absolute top-0 start-0 bg-success text-white fw-bold px-2 py-1 small z-1">
                                <i class="fas fa-check-circle me-1"></i> Tersedia
                            </span>

                            <!-- Harga -->
                            <div class="position-absolute bottom-0 start-0 w-100 bg-white bg-opacity-95 p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary fs-5">
                                        Rp{{ number_format($lapangan->harga, 0, ',', '.') }}
                                    </span>
                                    <small class="text-muted">/ jam</small>
                                </div>
                            </div>
                        </div>

                        <!-- Konten -->
                        <div class="card-body d-flex flex-column p-3">
                            <h5 class="card-title fw-bold mb-1">{{ $lapangan->nama }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i> 
                                {{ Str::limit($lapangan->lokasi, 35) }}
                            </p>

                            <div class="d-flex align-items-center text-muted small mb-3">
                                <i class="fas fa-users me-1"></i>
                                <span>{{ $lapangan->kapasitas }} pemain</span>
                                @if($lapangan->partner && $lapangan->partner->rating ?? null)
                                    <span class="ms-2">
                                        <i class="fas fa-star text-warning me-1"></i> 
                                        {{ number_format($lapangan->partner->rating, 1) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Tombol -->
                            <div class="mt-auto d-grid">
                                <a href="{{ route('booking.order-now', $lapangan->id) }}" 
                                   class="btn btn-brand py-2">
                                    <i class="fas fa-calendar-plus me-1"></i> Pesan Sekarang
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
            
            <h2 class="fw-bold text-muted mb-3">Lapangan Tidak Ditemukan</h2>
            <p class="text-muted mb-4 px-3">
                Coba ubah kata kunci pencarian, atau hubungi partner untuk info ketersediaan.
            </p>
            
            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                <a href="{{ route('home') }}" class="btn btn-outline-brand px-4 py-2">
                    <i class="fas fa-home me-1"></i> Kembali ke Beranda
                </a>
                <a href="https://wa.me/628123456789" target="_blank" class="btn btn-success px-4 py-2">
                    <i class="fab fa-whatsapp me-1"></i> Hubungi Kami
                </a>
            </div>
        </div>
    @endif

    <!-- Tips -->
    <div class="alert alert-light border rounded-3 mt-5">
        <div class="d-flex">
            <div class="flex-shrink-0 me-3 mt-1">
                <i class="fas fa-lightbulb text-brand fa-lg"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-2">Tips Booking Lapangan</h5>
                <ul class="mb-0 small text-muted">
                    <li>Booking minimal 1 jam sebelum jadwal</li>
                    <li>Durasi 2+ jam → diskon 10%</li>
                    <li>Tambahkan produk (minuman, bola) saat checkout</li>
                    <li>Partner dengan rating tinggi direkomendasikan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow { transition: box-shadow 0.3s ease; }
.hover-shadow:hover { box-shadow: 0 8px 16px rgba(0,0,0,0.12) !important; }
.z-1 { z-index: 1; }
</style>
@endsection