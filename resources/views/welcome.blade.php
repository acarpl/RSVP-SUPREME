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

                @forelse($lapangan as $item)
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

    {{-- SECTION ABOUT --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center g-5">

                {{-- IMAGE --}}
                <div class="col-md-5">
                    <img 
                        src="https://froyonion.sgp1.cdn.digitaloceanspaces.com/images/blogdetail/f6bc1a4cc74afe4bec3d20338a577cf830d51388.jpg"
                        class="img-fluid rounded"
                        style="max-height: 400px; object-fit: cover;">
                </div>

                {{-- TEXT --}}
                <div class="col-md-7">
                    <h2 class="fw-bold display-6 text-brand">Tentang Sportykuy</h2>
                    <p class="lead text-muted">All In One Booking Apps.</p>
                    <p>Aplikasi yang mempermudah kamu memesan lapangan favorit tanpa ribet dan tanpa antre.</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px;">
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
                                <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h5 class="h6 fw-bold mb-1">Pembayaran Aman</h5>
                                    <p class="small text-muted mb-0">Via Midtrans.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ROLE BUTTON DYNAMIC --}}
                    <div class="mt-3">
                        @auth

                            {{-- CUSTOMER --}}
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('partner.form') }}" class="btn btn-brand px-4 py-2">
                                    <i class="fas fa-users me-1"></i> Gabung Jadi Mitra
                                </a>

                            {{-- PARTNER --}}
                            @elseif(auth()->user()->role === 'partner')
                                <a href="{{ route('partner.dashboard') }}" 
                                    class="btn btn-success px-4 py-2 me-2">
                                    <i class="fas fa-edit me-1"></i> Halaman Mitra
                                </a>

                            {{-- SUPER ADMIN --}}
                            @elseif(auth()->user()->role === 'super_admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-dark px-4 py-2">
                                    <i class="fas fa-tools me-1"></i> Admin Panel
                                </a>

                            @endif

                        @else
                            {{-- USER BELUM LOGIN --}}
                            <a href="{{ route('login') }}" class="btn btn-brand px-4 py-2">
                                <i class="fas fa-sign-in-alt me-1"></i> Login untuk Menjadi Mitra
                            </a>
                        @endauth
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
@endsection
