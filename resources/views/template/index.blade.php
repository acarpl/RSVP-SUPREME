@extends('template.template')

@section('title', 'Homepage - SportyKuy')
@section('content')

@include('components.hero-carousel')

<div class="bg-danger text-white text-center py-5" style="margin-top: -1px;">
    <h2 class="mb-3 fw-bold">#Kaburajadulu</h2>
    <p class="lead mb-4 px-3">
        Capek kerja? Stres kuliah? Jangan diem di rumah!<br>
        Booking lapangan, ajak teman, dan main sampai lupa masalah!
    </p>
    <a href="" class="btn btn-light btn-lg rounded-pill px-4 fw-bold">
        Cari Lapangan Sekarang
    </a>
</div>
@endsection