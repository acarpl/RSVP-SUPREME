@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">

    {{-- 🔁 Hero Carousel --}}
    @include('components.hero-carousel')

    {{-- 🟥 Section #Kaburajadulu (ramping) --}}
    <section id="kaburajadulu" class="section-kabur py-4">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold display-6">#Kaburajadulu</h2>
                <p class="text-white opacity-90 mt-2">
                    Capek kerja? Stres kuliah? Jangan diem di rumah!
                </p>
            </div>

            {{-- 🏟 Grid Lapangan --}} 
            <div class="row g-3 justify-content-center">
                @foreach([
                    [
                        'title' => 'Futsal Arena',
                        'location' => 'Kelapa Gading, Jakarta',
                        'capacity' => '12 Orang',
                        'price' => 'Rp 250.000 / Jam',
                        'image' => 'https://placehold.co/400x250/0040ff/FFFFFF?text=Futsal&font=Plus+Jakarta+Sans'
                    ],
                    [
                        'title' => 'Basket Court 3x3',
                        'location' => 'BSD City, Tangerang',
                        'capacity' => '6 Orang',
                        'price' => 'Rp 180.000 / Jam',
                        'image' => 'https://placehold.co/400x250/FF7A6F/FFFFFF?text=Basket&font=Plus+Jakarta+Sans'
                    ],
                    [
                        'title' => 'Tennis Lapangan',
                        'location' => 'Senayan, Jakarta',
                        'capacity' => '4 Orang',
                        'price' => 'Rp 500.000 / Jam',
                        'image' => 'https://placehold.co/400x250/22c55e/FFFFFF?text=Tennis&font=Plus+Jakarta+Sans'
                    ]
                ] as $field)
                    <div class="col-md-4">
                        @component('components.field-card', $field)
                            <div class="d-grid gap-2 mt-2">
                                <a href="" class="btn btn-sm btn-brand">
                                    <i class="fas fa-calendar-check me-1"></i> Booking
                                </a>
                            </div>
                        @endcomponent
                    </div>
                @endforeach
            </div>

            {{-- 🔘 Tombol View More --}}
            <div class="text-center mt-4">
                <a href="{{ route('lapangan.index') }}" class="btn btn-view-more px-4 py-2">
                    <i class="fas fa-arrow-right me-1"></i> Lihat Semua
                </a>
            </div>
        </div>
    </section>

        {{-- 🟦 Section About Us --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center g-5">
                {{-- Gambar Ilustrasi --}}
                <div class="col-md-5">
                    <img 
                        src="https://froyonion.sgp1.cdn.digitaloceanspaces.com/images/blogdetail/f6bc1a4cc74afe4bec3d20338a577cf830d51388.jpg" 
                        alt="Tim Sportykuy" 
                        class="img-fluid rounded"
                        style="max-height: 400px; object-fit: contain;"
                    >
                </div>

                {{-- Konten --}}
                <div class="col-md-7">
                    <h2 class="fw-bold display-6 text-brand">Tentang Sportykuy</h2>
                    <p class="lead text-muted">
                        All In One Booking Apps.
                    </p>
                    <p class="mb-4">
                        Kami hadir untuk mempermudah kamu menemukan, memesan, dan menikmati momen seru bersama teman — tanpa ribet, tanpa waiting list.
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <h5 class="h6 fw-bold mb-1">100+ Venue Terpercaya</h5>
                                    <p class="small text-muted mb-0">Partner resmi dari lapangan terbaik di Jabodetabek.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h5 class="h6 fw-bold mb-1">Pembayaran Aman</h5>
                                    <p class="small text-muted mb-0">Via Midtrans</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
    @auth
        {{-- User Customer → tawarkan Gabung Mitra --}}
        @if (auth()->user()->role === 'customer')
            <form action="{{ route('partner.form') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-brand px-4 py-2">
                    <i class="fas fa-users me-1"></i> Gabung Jadi Mitra
                </button>
            </form>

        {{-- User Partner → tampilkan tombol kelola lapangan --}}
        @elseif(auth()->user()->role === 'partner')
            <a href="{{ route('lapangan.index') }}" class="btn btn-success px-4 py-2">
                <i class="fas fa-edit me-1"></i> Kelola Lapangan Saya
            </a>

        {{-- Super Admin → akses admin panel --}}
        @elseif(auth()->user()->role === 'super_admin')
            <a href="{{ route('lapangan.index') }}" class="btn btn-dark px-4 py-2">
                <i class="fas fa-tools me-1"></i> Admin Panel Lapangan
            </a>
        @endif

    @else
        {{-- Belum login --}}
        <a href="{{ route('login') }}" class="btn btn-brand px-4 py-2">
            <i class="fas fa-sign-in-alt me-1"></i> Login untuk Menjadi Mitra
        </a>
    @endauth

</div>
                </div>
            </div>
        </div>
    </section>
@endsection