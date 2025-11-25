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
    <div class="d-flex gap-2">
        <a href="{{ route('partner.lapangan.index') }}" class="btn btn-brand px-4 py-2">
            <i class="fas fa-futbol me-1"></i> Kelola Lapangan
        </a>

        <a href="{{ route('partner.products.manage') }}" class="btn btn-brand px-4 py-2">
            <i class="fas fa-store me-1"></i> Kelola Product
        </a>

        <a href="{{ route('vouchers.index') }}" class="btn btn-brand px-4 py-2">
            <i class="fas fa-tag me-1"></i> Kelola Voucher
        </a>
    </div>

</div>


    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistik Ringkasan -->
    <div class="row g-4 mb-4">
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
                    <small class="text-muted">Jumlah venue</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-success fw-bold mb-0">Booking</h6>
                            <h2 class="mb-0">{{ $totalBooking }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-calendar-check text-success fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Total sejak bergabung</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-warning border-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-warning fw-bold mb-0">Aktif</h6>
                            <h2 class="mb-0">{{ $bookingAktif }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Booking menunggu</small>
                </div>
            </div>
        </div>
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
                            <i class="fas fa-wallet text-light fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Booking terkonfirmasi</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Grafik Booking -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2"></i> Aktivitas Booking (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bell me-2"></i> Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($latestBookings as $booking)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $booking->lapangan->nama ?? 'Lapangan' }}</h6>
                                        <small class="text-muted">
                                            {{ $booking->jam_mulai?->format('d M H:i') ?? '-' }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : 'warning' }} text-dark">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <span class="fw-bold text-primary">Rp {{ number_format($booking->total_price) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center py-4">
                                <i class="fas fa-calendar-alt text-muted fa-2x mb-2"></i>
                                <p class="text-muted mb-0">Belum ada booking</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('booking.index') }}" class="btn btn-outline-brand btn-sm">
                            Lihat Semua Booking
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
                    <li>Upload foto lapangan berkualitas tinggi untuk meningkatkan booking</li>
                    <li>Respons cepat booking pelanggan untuk rating tinggi</li>
                    <li>Tambahkan produk (minuman, bola) untuk meningkatkan pendapatan</li>
                    <li>Update status lapangan ke "nonaktif" saat maintenance</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('bookingChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json(array_column($bookingsChart, 'date')),
            datasets: [{
                label: 'Jumlah Booking',
                data: @json(array_column($bookingsChart, 'count')),
                backgroundColor: '#D85C5C',
                borderColor: '#c24a4a',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection