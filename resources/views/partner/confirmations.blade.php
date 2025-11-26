@extends('layouts.app')

@section('title', 'Konfirmasi Booking')

@push('styles')
<style>
    .status-badge {
        font-size: 0.85em;
        padding: 0.35em 0.6em;
        border-radius: 0.3rem;
    }
    .status-badge.menunggu_konfirmasi { background-color: #fff3cd; color: #856404; }
    .status-badge.dikonfirmasi { background-color: #d4edda; color: #155724; }
    .status-badge.ditolak { background-color: #f8d7da; color: #721c24; }
    .dropdown-status-form { display: inline; }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-brand">
            <i class="fas fa-check-circle me-2"></i> Konfirmasi Booking
        </h1>
        <span class="badge bg-secondary">{{ $items->count() }} booking</span>
    </div>

    <!-- Filter Status -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filter Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                </div>
                <div class="col-md-7 text-end">
                    <a href="{{ route('partner.confirmations') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-sync me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Booking -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-futbol me-2"></i> Daftar Booking
            </h5>
            <small class="text-muted">Hanya booking dengan status <strong>Dibayar</strong></small>
        </div>
        <div class="card-body">
            @if($items->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada booking ditemukan.</h5>
                    <p class="text-muted">
                        @if($statusFilter)
                            Tidak ada booking dengan status <strong>{{ $statusOptions[$statusFilter] ?? $statusFilter }}</strong>.
                        @else
                            Belum ada customer yang booking & bayar.
                        @endif
                    </p>
                    <a href="{{ route('partner.lapangan.index') }}" class="btn btn-brand btn-sm">
                        <i class="fas fa-futbol me-1"></i> Kelola Lapangan Anda
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Pelanggan</th>
                                <th>Lapangan</th>
                                <th>Tanggal & Waktu</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr id="bk{{ $item['id'] }}">
                                <td><span class="badge bg-secondary">{{ $item['reference'] }}</span></td>
                                <td>{{ $item['customer'] }}</td>
                                <td>{{ $item['item'] }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $item['time'] }}</small>
                                </td>
                                <td class="fw-bold text-success">Rp {{ number_format($item['total']) }}</td>
                                <td>
                                    <span class="status-badge {{ $item['status'] }}">
                                        @if($item['status'] === 'menunggu_konfirmasi') ⏳ Menunggu
                                        @elseif($item['status'] === 'dikonfirmasi') ✅ Dikonfirmasi
                                        @elseif($item['status'] === 'ditolak') ❌ Ditolak
                                        @endif
                                    </span>
                                    @if($item['catatan'] !== '-')
                                        <br><small class="text-muted">{{ Str::limit($item['catatan'], 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($item['status'] === 'menunggu_konfirmasi')
                                        <!-- Dropdown Status -->
                                        <form 
                                            action="{{ route('partner.confirmations.update-status') }}" 
                                            method="POST" 
                                            class="dropdown-status-form"
                                        >
                                            @csrf
                                            <input type="hidden" name="type" value="booking">
                                            <input type="hidden" name="id" value="{{ $item['id'] }}">

                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="menunggu_konfirmasi" selected>⏳ Menunggu</option>
                                                <option value="dikonfirmasi">✅ Dikonfirmasi</option>
                                                <option value="ditolak">❌ Ditolak</option>
                                            </select>

                                            <input 
                                                type="text" 
                                                name="catatan" 
                                                class="form-control form-control-sm mt-1" 
                                                placeholder="Catatan (opsional)"
                                                maxlength="100"
                                            >
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Tips -->
    <div class="alert alert-info border-0 rounded-3 mt-4">
        <div class="d-flex">
            <div class="flex-shrink-0 me-3 mt-1">
                <i class="fas fa-lightbulb text-info fa-lg"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1">Tips Konfirmasi Booking</h6>
                <ul class="mb-0 small">
                    <li><strong>Konfirmasi dalam 1 jam</strong> setelah pembayaran untuk rating tinggi</li>
                    <li>Berikan catatan jelas jika ditolak (misal: "Lapangan sedang maintenance")</li>
                    <li>Booking yang dikonfirmasi akan muncul di jadwal lapangan Anda</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit saat ganti status
    document.querySelectorAll('select[name="status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush