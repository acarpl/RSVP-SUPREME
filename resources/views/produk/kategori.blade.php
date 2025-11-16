@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h2 class="text-center fw-bold mb-4">Pilih Kategori Produk</h2>
    <p class="text-center text-muted mb-5">Cari perlengkapan olahraga atau kebutuhan makanan & minuman selama bermain.</p>

    <div class="row justify-content-center g-4">

        {{-- Kategori Alat --}}
        <div class="col-md-4">
            <a href="{{ route('products.index', ['kategori' => 'alat']) }}" 
               class="text-decoration-none">
                <div class="card shadow-sm border-0 kategori-card text-center p-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/2965/2965875.png" width="80" class="mx-auto mb-3">
                    <h4 class="fw-bold">Alat Olahraga</h4>
                    <p class="text-muted">Bola, raket, rompi, cone, matras, dan lainnya.</p>
                </div>
            </a>
        </div>

        {{-- Kategori Makanan --}}
        <div class="col-md-4">
            <a href="{{ route('products.index', ['kategori' => 'makanan']) }}" 
               class="text-decoration-none">
                <div class="card shadow-sm border-0 kategori-card text-center p-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/857/857681.png" width="80" class="mx-auto mb-3">
                    <h4 class="fw-bold">Makanan & Minuman</h4>
                    <p class="text-muted">Air mineral, snack, makanan cepat saji, dll.</p>
                </div>
            </a>
        </div>

    </div>
</div>

<style>
.kategori-card {
    transition: all 0.2s ease-in-out;
    border-radius: 16px;
}
.kategori-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
</style>
@endsection
