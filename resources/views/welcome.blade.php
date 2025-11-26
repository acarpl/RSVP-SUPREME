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

            <!-- TEXT (kiri / atas di mobile) -->
            <div class="col-lg-6">
                <h2 class="fw-bold display-6 text-brand mb-3">Tentang Sportykuy</h2>
                <p class="lead text-muted mb-4">All-in-One Booking Apps untuk Lapangan Olahraga.</p>

                <p class="mb-4">
                    Sportykuy memudahkan pelanggan memesan lapangan futsal, badminton, atau basket secara instan — sekaligus membantu mitra mengelola jadwal, pembayaran, dan laporan harian dalam satu sistem terintegrasi.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2 fs-5"></i>
                            <div>
                                <h6 class="fw-bold mb-0">100+ Venue Aktif</h6>
                                <small class="text-muted">Jabodetabek & terus berkembang</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-shield-check text-primary mt-1 me-2 fs-5"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Pembayaran Aman</h6>
                                <small class="text-muted">Midtrans & rekonsiliasi otomatis</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOUNDER (kanan / bawah di mobile) -->
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4 text-center text-lg-start">Dibangun oleh Tim</h3>

                <div class="row g-4">
                    @php
                        $founders = [
                            ['name' => 'I\'Zaz Ramdhany', 'role' => 'System & Full-Stack Developer', 'img' => 'https://github.com/acarpl/RSVP-SUPREME/blob/main/WhatsApp%20Image%202025-11-22%20at%2013.43.02_395e63db.jpg?raw=true'],
                            ['name' => 'Rasya Falqi Gani', 'role' => 'UI/UX Designer & Full-Stack Developer', 'img' => 'https://github.com/acarpl/RSVP-SUPREME/blob/main/WhatsApp%20Image%202025-11-22%20at%2013.43.09_e2479526.jpg?raw=true'],
                            ['name' => 'Zidan Fakhry Mylan', 'role' => 'System & Full-Stack Developer', 'img' => 'https://github.com/acarpl/RSVP-SUPREME/blob/main/WhatsApp%20Image%202025-11-22%20at%2013.43.10_9bd4d4c6.jpg?raw=true'],
                        ];
                    @endphp

                    @foreach($founders as $founder)
                        <div class="col-md-4">
                            <div class="text-center">
                                <img 
                                    src="{{ $founder['img'] }}" 
                                    alt="{{ $founder['name'] }}"
                                    class="rounded-circle mb-3 border"
                                    style="width: 140px; height: 140px; object-fit: cover; object-position: center;"
                                    loading="lazy">
                                <h6 class="fw-bold mb-1">{{ $founder['name'] }}</h6>
                                <p class="text-muted small mb-0">{{ $founder['role'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
</div>
@endsection