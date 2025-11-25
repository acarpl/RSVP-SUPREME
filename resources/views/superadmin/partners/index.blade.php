@extends('layouts.superadmin')

@section('title', 'Kelola Partner')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Partner</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h5 class="mb-0"><i class="fas fa-building me-2"></i> Daftar Partner</h5>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('register') }}?role=partner" target="_blank" class="btn btn-outline-brand">
            <i class="fas fa-user-plus me-1"></i> Daftarkan Partner Baru
        </a>
    </div>
</div>

<div class="row g-4">
    @forelse($partners as $partner)
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $partner->name }}</h6>
                        <small class="text-muted">{{ $partner->email }}</small>
                    </div>
                    <span class="badge bg-success">Partner</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex justify-content-between mb-3">
                    <div class="text-center">
                        <div class="fw-bold text-primary">{{ $partner->lapangan_count }}</div>
                        <small class="text-muted">Lapangan</small>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold text-success">{{ $partner->booking_count ?? 0 }}</div>
                        <small class="text-muted">Booking</small>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold">{{ $partner->created_at->format('Y') }}</div>
                        <small class="text-muted">Sejak</small>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('superadmin.partners.show', $partner) }}" 
                       class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-eye me-1"></i> Detail & Lapangan
                    </a>
                    <button type="button" 
                            class="btn btn-sm btn-outline-warning w-100"
                            data-bs-toggle="modal" 
                            data-bs-target="#suspendModal{{ $partner->id }}">
                        <i class="fas fa-pause me-1"></i> Nonaktifkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Nonaktifkan -->
        <div class="modal fade" id="suspendModal{{ $partner->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nonaktifkan Partner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menonaktifkan partner <strong>{{ $partner->name }}</strong>?</p>
                        <p class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i> 
                            Partner akan berubah menjadi customer biasa dan tidak bisa mengelola lapangan.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('superadmin.partners.suspend', $partner) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-pause me-1"></i> Ya, Nonaktifkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-building text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-muted">Belum ada partner</h5>
            <p class="text-muted">Partner dapat mendaftar melalui halaman registrasi.</p>
            <a href="{{ route('register') }}?role=partner" target="_blank" class="btn btn-brand mt-2">
                <i class="fas fa-user-plus me-1"></i> Daftar Partner
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($partners->isNotEmpty())
<div class="mt-4">
    {{ $partners->links() }}
</div>
@endif
@endsection