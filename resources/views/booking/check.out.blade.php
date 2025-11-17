@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mb-4">
                <div class="bg-brand text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 60px; height: 60px;">
                    <i class="fas fa-check fa-2x"></i>
                </div>
                <h2 class="fw-bold text-brand">Konfirmasi Booking</h2>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <!-- Detail Booking -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Detail Pesanan</h5>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Lapangan</span>
                                <span class="fw-medium">{{ $booking->lapangan->nama }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Jadwal</span>
                                <span class="small text-muted">
                                    {{ $booking->start_time->format('d M Y H:i') }} - 
                                    {{ $booking->end_time->format('H:i') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Durasi</span>
                                <span>{{ $booking->duration_hours }} jam</span>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span class="text-primary fs-4">Rp {{ number_format($booking->total_price) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Metode Pembayaran</h5>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment" id="midtrans" value="midtrans" checked>
                            <label class="form-check-label" for="midtrans">
                                <i class="fab fa-cc-visa me-2"></i> Kartu Kredit/Debit
                            </label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment" id="bank" value="bank">
                            <label class="form-check-label" for="bank">
                                <i class="fas fa-university me-2"></i> Transfer Bank
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="ewallet" value="ewallet">
                            <label class="form-check-label" for="ewallet">
                                <i class="fas fa-wallet me-2"></i> E-Wallet (GoPay, OVO, DANA)
                            </label>
                        </div>
                    </div>

                    <!-- Tombol Bayar -->
                    <div class="d-grid">
                        <button type="button" 
                                class="btn btn-brand py-3"
                                onclick="alert('Fitur payment dalam pengembangan. Untuk demo, booking langsung dikonfirmasi.')">
                            <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-lock me-1"></i> Pembayaran aman • Batal kapan saja
                        </small>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="alert alert-light border-0 rounded-3 mt-4">
                <h6 class="fw-bold mb-2">Kebijakan Sportykuy</h6>
                <ul class="mb-0 small text-muted">
                    <li>Pembatalan 2 jam sebelum jadwal → refund 100%</li>
                    <li>Keterlambatan > 30 menit → booking hangus</li>
                    <li>Gratis antar-jemput untuk durasi > 3 jam</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Simulasi pembayaran
document.querySelector('button[onclick]').addEventListener('click', function() {
    fetch("{{ route('booking.confirm', $booking->id) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = "{{ route('booking.show', $booking->id) }}";
        }
    });
});
</script>
@endpush
@endsection