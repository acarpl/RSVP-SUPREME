@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="container py-4">
    <h1 class="fw-bold text-brand mb-4">Konfirmasi Pembayaran</h1>

    <!-- Booking Menunggu Konfirmasi -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-futbol text-info me-2"></i> Booking Lapangan</h5>
        </div>
        <div class="card-body">
            @if($bookings->isEmpty())
                <p class="text-muted">Tidak ada booking menunggu konfirmasi.</p>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Lapangan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr>
                                <td>#BK-{{ $booking->id }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->lapangan->nama }}</td>
                                <td>{{ $booking->tanggal->format('d M Y') }}<br>
                                    {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}
                                </td>
                                <td class="fw-bold">Rp {{ number_format($booking->total_harga) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success" 
                                            data-bs-toggle="modal" data-bs-target="#confirmModal{{ $booking->id }}">
                                        <i class="fas fa-check me-1"></i> Konfirmasi
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Konfirmasi -->
                            <div class="modal fade" id="confirmModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('partner.booking.confirm', $booking) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Booking #{{ $booking->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status" value="dikonfirmasi" id="confirm-{{ $booking->id }}" checked>
                                                        <label class="form-check-label" for="confirm-{{ $booking->id }}">
                                                            <i class="fas fa-check text-success me-1"></i> Disetujui
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status" value="ditolak" id="reject-{{ $booking->id }}">
                                                        <label class="form-check-label" for="reject-{{ $booking->id }}">
                                                            <i class="fas fa-times text-danger me-1"></i> Ditunda/Ditolak
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan (Opsional)</label>
                                                    <textarea name="catatan" class="form-control" rows="3" placeholder="Alasan penolakan atau catatan khusus..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane me-1"></i> Kirim Konfirmasi
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Orders (Opsional) -->
    {{-- 
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-box text-primary me-2"></i> Pesanan Produk</h5>
        </div>
        <!-- ... sama seperti booking ... -->
    </div>
    --}}
</div>
@endsection