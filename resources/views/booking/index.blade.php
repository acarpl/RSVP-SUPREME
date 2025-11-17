@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-brand">Booking Saya</h1>
        <a href="{{ route('lapangan.index') }}" class="btn btn-outline-brand">
            <i class="fas fa-futbol me-1"></i> Booking Baru
        </a>
    </div>

    @if($bookings->count())
        <div class="row g-4">
            @foreach($bookings as $booking)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-futbol text-brand"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $booking->lapangan->nama }}</h5>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark">
                                            {{ $booking->start_time->format('d M Y H:i') }}
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            {{ $booking->duration_hours }} jam
                                        </span>
                                        <span class="badge 
                                            @if($booking->status == 'confirmed') bg-success text-white
                                            @elseif($booking->status == 'pending') bg-warning text-dark
                                            @else bg-secondary text-white @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">Rp {{ number_format($booking->total_price) }}</div>
                                    <a href="{{ route('booking.show', $booking) }}" class="btn btn-sm btn-brand mt-2">
                                        Detail
                                    </a>
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
                <i class="fas fa-calendar-alt fa-2x text-muted"></i>
            </div>
            <h3 class="fw-bold text-muted mb-2">Belum Ada Booking</h3>
            <p class="text-muted mb-4">Mari mulai booking lapangan favoritmu!</p>
            <a href="{{ route('lapangan.index') }}" class="btn btn-brand px-4 py-2">
                <i class="fas fa-futbol me-1"></i> Cari Lapangan
            </a>
        </div>
    @endif
</div>
@endsection