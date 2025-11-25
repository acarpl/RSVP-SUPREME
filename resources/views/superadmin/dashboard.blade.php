@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Total Users -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card border-0">
            <div class="card-body d-flex">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ms-3">
                    <div class="stat-value">{{ $totalUsers }}</div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Partners -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card border-0">
            <div class="card-body d-flex">
                <div class="stat-icon bg-success">
                    <i class="fas fa-building"></i>
                </div>
                <div class="ms-3">
                    <div class="stat-value">{{ $totalPartners }}</div>
                    <div class="stat-label">Partner Terdaftar</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Lapangan -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card border-0">
            <div class="card-body d-flex">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-futbol"></i>
                </div>
                <div class="ms-3">
                    <div class="stat-value">{{ $totalLapangan }}</div>
                    <div class="stat-label">Lapangan Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Produk -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card border-0">
            <div class="card-body d-flex">
                <div class="stat-icon bg-danger">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="ms-3">
                    <div class="stat-value">{{ $totalProduk }}</div>
                    <div class="stat-label">Produk Tersedia</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Fitur riwayat aktivitas akan dikembangkan di tahap berikutnya.</p>
            </div>
        </div>
    </div>
</div>
@endsection