@extends('layouts.app')

@section('title', 'Dashboard Mitra')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <!-- KIRI: Judul & Salam -->
        <div class="mb-3 mb-md-0">
            <h1 class="fw-bold text-brand mb-1">
                <i class="fas fa-chart-line me-2"></i> Dashboard Mitra
            </h1>
            <p class="text-muted mb-0">
                Halo, <strong>{{ auth()->user()->nama_usaha ?? auth()->user()->name }}</strong>!
            </p>
        </div>

        <!-- KANAN: Tombol -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('partner.lapangan.index') }}" class="btn btn-brand px-4 py-2">
                <i class="fas fa-futbol me-1"></i> Kelola Lapangan
            </a>
            <a href="{{ route('partner.products.manage') }}" class="btn btn-brand px-4 py-2">
                <i class="fas fa-store me-1"></i> Kelola Produk
            </a>
            <a href="{{ route('partner.vouchers.index') }}" class="btn btn-brand px-4 py-2">
                <i class="fas fa-tag me-1"></i> Kelola Voucher
            </a>
            <!-- 🔔 Tambahkan tombol konfirmasi -->
            <a href="{{ route('partner.confirmations') }}" class="btn btn-success px-4 py-2 position-relative">
                <i class="fas fa-check-circle me-1"></i> Konfirmasi
                @if($pendingConfirmations > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $pendingConfirmations }}
                        <span class="visually-hidden">booking menunggu</span>
                    </span>
                @endif
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistik Ringkasan - Diperbarui -->
    <div class="row g-4 mb-4">
        <!-- Lapangan -->
        <div class="col-md-3">
            <div class="card border-start border-primary border-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-primary fw-bold mb-0">Lapangan</h6>
                            <h2 class="mb-0">{{ $totalLapangan }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-futbol text-primary fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Jumlah venue aktif</small>
                </div>
            </div>
        </div>

        <!-- Booking Berhasil -->
        <div class="col-md-3">
            <div class="card border-start border-success border-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-success fw-bold mb-0">Berhasil</h6>
                            <h2 class="mb-0">{{ $confirmedBookings }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Booking dikonfirmasi</small>
                </div>
            </div>
        </div>

        <!-- Menunggu Konfirmasi -->
        <div class="col-md-3">
            <div class="card border-start border-warning border-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-warning fw-bold mb-0">Menunggu</h6>
                            <h2 class="mb-0">{{ $pendingConfirmations }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Perlu konfirmasi</small>
                </div>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="col-md-3">
            <div class="card border-start border-brand border-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-brand fw-bold mb-0">Pendapatan</h6>
                            <h2 class="mb-0">Rp {{ number_format($pendapatan) }}</h2>
                        </div>
                        <div class="bg-brand bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-wallet text-white fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Total terkonfirmasi</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Grafik Booking -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar me-2"></i> 
                        Aktivitas Booking (30 Hari Terakhir)
                    </h5>
                    <span class="badge bg-primary">Total: {{ $totalBooking }} booking</span>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Konfirmasi -->
        <div class="col-lg-4">
            <!-- Card Konfirmasi Cepat -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2"></i> Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('partner.confirmations') }}" 
                           class="btn btn-outline-success d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fas fa-check-circle me-2"></i> 
                                Konfirmasi Booking
                            </span>
                            @if($pendingConfirmations > 0)
                                <span class="badge bg-danger">{{ $pendingConfirmations }}</span>
                            @endif
                        </a>
                        <a href="{{ route('partner.lapangan.create') }}" 
                           class="btn btn-outline-primary d-flex align-items-center">
                            <i class="fas fa-plus-circle me-2"></i> 
                            Tambah Lapangan Baru
                        </a>
                        <a href="{{ route('partner.products.create') }}" 
                           class="btn btn-outline-brand d-flex align-items-center">
                            <i class="fas fa-plus me-2"></i> 
                            Tambah Produk
                        </a>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bell me-2"></i> 
                        Booking Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($latestBookings as $booking)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $booking->lapangan->nama ?? 'Lapangan' }}</h6>
                                        <small class="text-muted d-block">
                                            {{ $booking->user->name ?? 'Customer' }} • 
                                            {{ $booking->tanggal?->format('d M') ?? '-' }}
                                            {{ $booking->jam_mulai ?? '00:00' }} - {{ $booking->jam_selesai ?? '00:00' }}
                                        </small>
                                    </div>
                                    <span class="badge 
                                        @if($booking->partner_status == 'dikonfirmasi') bg-success text-white
                                        @elseif($booking->partner_status == 'ditolak') bg-danger text-white
                                        @else bg-warning text-dark @endif">
                                        {{ ucfirst(str_replace('_', ' ', $booking->partner_status)) }}
                                    </span>
                                </div>
                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">Rp {{ number_format($booking->total_harga) }}</span>
                                    @if($booking->partner_status == 'menunggu_konfirmasi')
                                        <a href="{{ route('partner.confirmations') }}#bk{{ $booking->id }}" 
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Konfirmasi
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center py-4">
                                <div class="mb-2">
                                    <i class="fas fa-calendar-alt text-muted fa-2x"></i>
                                </div>
                                <p class="text-muted mb-0">
                                    Belum ada booking<br>
                                    <small class="text-muted">Booking akan muncul di sini setelah customer bayar</small>
                                </p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('partner.confirmations') }}" class="btn btn-outline-brand btn-sm">
                            <i class="fas fa-history me-1"></i> Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tips Mitra -->
    <div class="alert alert-info border-0 rounded-3 mt-4">
        <div class="d-flex">
            <div class="flex-shrink-0 me-3 mt-1">
                <i class="fas fa-lightbulb text-info fa-lg"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-2">Tips untuk Mitra</h5>
                <ul class="mb-0 small">
                    <li><strong>Konfirmasi cepat</strong> booking yang sudah dibayar untuk rating tinggi</li>
                    <li>Upload foto lapangan berkualitas tinggi untuk meningkatkan booking</li>
                    <li>Tambahkan produk (minuman, bola) untuk tingkatkan pendapatan 20-30%</li>
                    <li>Update status lapangan ke "nonaktif" saat maintenance</li>
                    <li>Beri voucher diskon untuk customer setia</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Status Legend -->
    <div class="bg-light p-3 rounded-3 mt-4">
        <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-2"></i> Status Booking</h6>
        <div class="d-flex flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <span class="badge bg-success me-2"></span> 
                <small>Dikonfirmasi (Siap digunakan)</small>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-warning text-dark me-2"></span> 
                <small>Menunggu Konfirmasi (Sudah dibayar)</small>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-danger me-2"></span> 
                <small>Ditolak (Lapangan tidak tersedia)</small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Konfirmasi otomatis scroll ke booking tertentu
    @if(request()->has('scroll'))
        const element = document.getElementById('bk{{ request()->get("scroll") }}');
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            element.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => element.classList.remove('animate__animated', 'animate__pulse'), 2000);
        }
    @endif

    // Chart Booking
    const ctx = document.getElementById('bookingChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json(array_column($bookingsChart, 'date')),
                datasets: [
                    {
                        label: 'Menunggu Konfirmasi',
                        data: @json(array_column($bookingsChart, 'pending')),
                        borderColor: '#FFC107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Dikonfirmasi',
                        data: @json(array_column($bookingsChart, 'confirmed')),
                        borderColor: '#28A745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
});
</script>
@endpush
@endsection