@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="fw-bold text-brand mb-0">Riwayat Pesanan</h1>
    </div>

    @if($bookings->count())
        <div class="row g-4">
            @foreach($bookings as $booking)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row justify-content-between">
                                <div>
                                    <h5 class="mb-1">
                                        @if($booking->alamat_pengiriman)
                                            <i class="fas fa-box text-primary me-2"></i> Beli Produk
                                        @else
                                            <i class="fas fa-futbol text-success me-2"></i> Sewa Alat
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-2">
                                        {{ $booking->created_at->format('d M Y H:i') }}
                                    </p>
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        @foreach($booking->items as $item)
                                            <span class="badge bg-light text-dark">
                                                {{ $item->quantity }}x {{ Str::limit($item->name, 15) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary fs-5">
                                        Rp {{ number_format($booking->total_harga) }}
                                    </div>
                                    <span class="badge 
                                        @if($booking->status == 'dibayar') bg-success text-white
                                        @elseif($booking->status == 'menunggu_pembayaran') bg-warning text-dark
                                        @else bg-secondary text-white @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <div class="mt-2">
                                        <a href="{{ route('riwayat.show', $booking) }}" 
                                           class="btn btn-sm btn-outline-brand">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $bookings->links() }}

    @else
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                 style="width: 80px; height: 80px;">
                <i class="fas fa-history fa-2x text-muted"></i>
            </div>
            <h3 class="fw-bold text-muted mb-2">Belum Ada Pesanan</h3>
            <p class="text-muted mb-4">Riwayat pesanan Anda akan muncul di sini setelah berhasil checkout.</p>
            <a href="{{ route('products.index') }}" class="btn btn-brand px-4 py-2">
                <i class="fas fa-store me-1"></i> Belanja Sekarang
            </a>
        </div>
    @endif
</div>
@endsection