@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <div class="bg-brand text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="fas fa-credit-card fa-2x"></i>
                </div>
                <h2 class="fw-bold text-brand">Bayar Sekarang</h2>
                <p class="text-muted">Booking #{{ $booking->id }} • Rp {{ number_format($booking->total_harga) }}</p>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div id="midtrans-snap"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function() {
            window.location.href = "{{ route('booking.show', $booking) }}";
        },
        onError: function() {
            window.location.href = "{{ route('booking.index') }}";
        }
    });
});
</script>
@endpush
@endsection