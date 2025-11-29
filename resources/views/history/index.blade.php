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

    @if($paginated->count())
        <div class="row g-4">
            @foreach($paginated as $item)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-1">
                                        @if($item->has_alamat)
                                            <i class="fas fa-box text-primary me-2"></i> Beli Produk
                                        @elseif($item->jenis === 'sewa_alat')
                                            <i class="fas fa-futbol text-success me-2"></i> Sewa Lapangan
                                        @elseif($item->jenis === 'lapangan')
                                            <i class="fas fa-futbol text-info me-2"></i> Booking Lapangan
                                        @else
                                            <i class="fas fa-receipt text-muted me-2"></i> Pesanan
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-0">
                                        {{ $item->created_at->format('d M Y H:i') }}
                                        <span class="badge bg-light text-dark ms-2">
                                            #{{ $item->type }}-{{ $item->id }}
                                        </span>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary fs-5">
                                        Rp {{ number_format($item->total_harga) }}
                                    </div>
                                    <span class="badge 
                                        @if($item->status == 'dibayar') bg-success text-white
                                        @elseif($item->status == 'menunggu_pembayaran') bg-warning text-dark
                                        @elseif($item->status == 'dibatalkan') bg-danger text-white
                                        @else bg-secondary text-white @endif">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $paginated->links() }}

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