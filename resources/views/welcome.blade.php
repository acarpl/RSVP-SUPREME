@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">

    <!-- 🟢 Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Booking Lapangan Mudah!</h1>
            <p class="lead mb-4">
                Cari, pesan, dan main bareng teman di 
                <span class="fw-bold text-success">Sportykuy ⚽🏸🏀</span>
            </p>

            @auth
                <a href="{{ route('booking.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-futbol me-2"></i> Pesan Sekarang
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-futbol me-2"></i> Pesan Sekarang (Login Dulu)
                </a>
            @endauth
        </div>
    </section>

    <!-- 🔴 Section #KaburAjaDulu -->
    <section id="kaburajadulu" class="py-5 bg-brand text-white text-center">
        <div class="container">
            <h2 class="display-6 fw-bold mb-3">#KaburAjaDulu</h2>
            <p class="lead mb-0">
                Capek kerja? Stres kuliah? Jangan diem di rumah! <br>
                Booking lapangan sekarang dan main bareng teman-temanmu 🎾⚽🏸
            </p>
        </div>
    </section>

</div>
@endsection