@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('riwayat.index') }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="fw-bold text-brand mb-0">Detail Pesanan</h1>
    </div>

    <div class="row g-4">
        <!-- Info Utama -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-receipt me-2"></i> 
                        Informasi Pesanan #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="text-muted mb-1">Jenis Pesanan</h6>
                            <h5 class="fw-bold">
                                @if($booking->alamat_pengiriman)
                                    <i class="fas fa-box text-primary me-1"></i> Beli Produk
                                @else
                                    <i class="fas fa-futbol text-success me-1"></i> Sewa Alat
                                @endif
                            </h5>
                        </div>
                        <div class="text-end">
                            <h6 class="text-muted mb-1">Status</h6>
                            <span class="badge 
                                @if($booking->status == 'dibayar') bg-success text-white
                                @elseif($booking->status == 'menunggu_pembayaran') bg-warning text-dark
                                @else bg-secondary text-white @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Alamat Pengiriman -->
                    @if($booking->alamat_pengiriman)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2"><i class="fas fa-map-marker-alt me-1"></i> Alamat Pengiriman</h6>
                            <div class="p-3 bg-light rounded">
                                {{ $booking->alamat_pengiriman }}
                            </div>
                        </div>
                    @endif

                    <!-- Jadwal Sewa -->
                    @if($booking->tanggal)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2"><i class="fas fa-calendar-check me-1"></i> Jadwal Sewa</h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <small class="text-muted">Tanggal</small><br>
                                    <strong>{{ $booking->tanggal->format('d M Y') }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Jam</small><br>
                                    <strong>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Durasi</small><br>
                                    <strong>{{ $booking->durasi }} jam</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Produk -->
                    <div>
                        <h6 class="text-muted mb-2"><i class="fas fa-box-open me-1"></i> Produk</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->items as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-end">Rp {{ number_format($item->price) }}</td>
                                            <td class="text-end">{{ $item->quantity }}</td>
                                            <td class="text-end fw-bold">
                                                Rp {{ number_format($item->price * $item->quantity) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td colspan="3">Total</td>
                                        <td class="text-end text-primary fs-5">
                                            Rp {{ number_format($booking->total_harga) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aksi -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-cogs me-2"></i> Aksi</h5>
                </div>
                <div class="card-body">
                    @if($booking->status == 'menunggu_pembayaran')
                        <a href="{{ route('payment.process', $booking) }}" 
                           class="btn btn-brand w-100 mb-2">
                            <i class="fas fa-credit-card me-1"></i> Bayar Sekarang
                        </a>
                    @endif

                    <a href="{{ route('booking.index') }}" class="btn btn-outline-brand w-100">
                        <i class="fas fa-calendar-check me-1"></i> Lihat Semua Booking
                    </a>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card border-0 shadow-sm rounded-3 mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i> Status</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 24px; height: 24px;">
                                <i class="fas fa-check fa-xs"></i>
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold">Pesanan Dibuat</div>
                                <small class="text-muted">{{ $booking->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="bg-{{ $booking->status == 'dibayar' ? 'success' : 'secondary' }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 24px; height: 24px;">
                                @if($booking->status == 'dibayar')
                                    <i class="fas fa-check fa-xs"></i>
                                @else
                                    <i class="fas fa-clock fa-xs"></i>
                                @endif
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold">
                                    @if($booking->status == 'dibayar')
                                        Pembayaran Berhasil
                                    @elseif($booking->status == 'menunggu_pembayaran')
                                        Menunggu Pembayaran
                                    @else
                                        Dibatalkan
                                    @endif
                                </div>
                                @if($booking->status == 'dibayar')
                                    <small class="text-muted">{{ $booking->updated_at->format('d M Y H:i') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection