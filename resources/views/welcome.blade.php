@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">

    {{-- 🔁 HERO SLIDER --}}
    @include('components.hero-carousel')

    {{-- SECTION: #Kaburajadulu --}}
    <section id="kaburajadulu" class="section-kabur py-4">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold display-6">#Kaburajadulu</h2>
                <p class="text-white opacity-90 mt-2">
                    Capek kerja? Stres kuliah? Saatnya olahraga!
                </p>
            </div>

            {{-- 🔥 GRID LAPANGAN DINAMIS --}}
            <div class="row g-3 justify-content-center">

                @forelse($lapangans as $item)
                    <div class="col-md-4">

                        @component('components.field-card')
                            @slot('title', $item->nama)
                            @slot('location', $item->lokasi)
                            @slot('capacity', $item->kapasitas . ' Orang')
                            
                            {{-- HARGA FIX --}}
                            @slot('price', 'Rp ' . number_format($item->harga, 0, ',', '.') . ' / Jam')

                            {{-- GAMBAR FIX --}}
                            @slot('image', $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-lapangan.jpg'))

                            {{-- BUTTON BOOKING --}}
                            <a href="{{ route('booking.order-now', $item->id) }}" 
                                class="btn btn-primary w-100 mt-2">
                                Booking Sekarang
                            </a>
                        @endcomponent

                    </div>
                @empty
                    <div class="col-12 text-center text-white-50">
                        <p>Belum ada lapangan yang tersedia.</p>
                    </div>
                @endforelse

            </div>

            {{-- BUTTON VIEW MORE --}}
            <div class="text-center mt-4">
                <a href="{{ route('lapangan.index') }}" class="btn btn-view-more px-4 py-2">
                    <i class="fas fa-arrow-right me-1"></i> Pilih Lapangan Lain
                </a>
            </div>

        </div>
    </section>

<section class="py-5 bg-dark text-white">
    <div class="container text-center">
        <span class="badge bg-brand text-white px-3 py-1 rounded-pill mb-3">
            Untuk Pemilik Lapangan
        </span>
        <h1 class="display-5 fw-bold mb-3">Kelola Lapangan Anda Lebih Mudah</h1>
        <p class="lead mb-4 px-md-5">
            Jadilah mitra Sportykuy dan dapatkan dashboard khusus untuk kelola booking, lapangan, promo, dan laporan harian — semua dalam satu aplikasi.
        </p>
        
        <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">
            @auth
                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('partner.form') }}" class="btn btn-brand btn-lg px-5">
                        <i class="fas fa-handshake me-2"></i>Gabung Jadi Mitra
                    </a>
                @elseif(auth()->user()->role === 'partner')
                    <a href="{{ route('partner.dashboard') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard Mitra
                    </a>
                @elseif(auth()->user()->role === 'super_admin')
                    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="fas fa-tools me-2"></i>Admin Panel
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-brand btn-lg px-5">
                    <i class="fas fa-sign-in-alt me-2"></i>Login & Mulai
                </a>
                <a href="{{ route('partner.form') }}" class="btn btn-outline-light btn-lg px-5">
                    <i class="fas fa-info-circle me-2"></i>Syarat Mitra
                </a>
            @endauth
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-5 align-items-center">

            {{-- TEXT (kiri) --}}
            <div class="col-lg-6">
                <h2 class="fw-bold display-6 text-brand">Tentang Sportykuy</h2>
                <p class="lead text-muted">All In One Booking Apps.</p>
                <p class="mb-4">
                    Platform yang memudahkan pelanggan memesan lapangan favorit, sekaligus membantu mitra mengelola bisnis lapangan secara digital — dari booking, pembayaran otomatis, hingga laporan harian.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h5 class="h6 fw-bold mb-1">100+ Venue</h5>
                                <p class="small text-muted mb-0">Partner resmi Jabodetabek.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h5 class="h6 fw-bold mb-1">Pembayaran Aman</h5>
                                <p class="small text-muted mb-0">Via Midtrans.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3 FOUNDER PHOTOS (kanan, vertikal) --}}
            <div class="col-lg-6">
                <div class="row g-4">

                    {{-- Founder 1 --}}
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <img 
                                src="https://via.placeholder.com/300x300/6a11cb/ffffff?text=RF" 
                                alt="I'Zaz Ramdhany"
                                class="rounded-circle me-3"
                                style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #f8f9fa;">
                            <div>
                                <h5 class="mb-0 fw-bold">I'Zaz Ramdhany</h5>
                                <p class="text-muted small mb-0">Developer</p>
                            </div>
                        </div>
                    </div>

                    {{-- Founder 2 --}}
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <img 
                                src="https://via.placeholder.com/300x300/2575fc/ffffff?text=AL" 
                                alt="Rasya"
                                class="rounded-circle me-3"
                                style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #f8f9fa;">
                            <div>
                                <h5 class="mb-0 fw-bold">Rasya Falqi Gani</h5>
                                <p class="text-muted small mb-0">Developer</p>
                            </div>
                        </div>
                    </div>

                    {{-- Founder 3 --}}
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <img 
                                src="https://via.placeholder.com/300x300/ff6b6b/ffffff?text=NS" 
                                alt="ZIdane"
                                class="rounded-circle me-3"
                                style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #f8f9fa;">
                            <div>
                                <h5 class="mb-0 fw-bold">Zidan Fakhry Mylan</h5>
                                <p class="text-muted small mb-0">Developer</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
</div>
@endsection