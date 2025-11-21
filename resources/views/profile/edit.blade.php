@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-brand rounded-circle me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="fw-bold text-brand mb-0">Profil Saya</h1>
                    <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="row g-4">

                <!-- Card Profil -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="position-relative">
                            <div class="bg-brand" style="height: 150px;"></div>
                            <div class="position-absolute bottom-0 start-50 translate-middle-x">
                                <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 100px; height: 100px; margin-bottom: -50px;">
                                    <span class="fw-bold text-brand" style="font-size: 36px;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-5 text-center">
                            <h4 class="mb-1">{{ auth()->user()->name }}</h4>

                            <div class="mb-3">
                                <span class="badge 
                                    @if(auth()->user()->role === 'partner') bg-success text-white
                                    @elseif(auth()->user()->role === 'super_admin') bg-dark text-white
                                    @else bg-light text-dark @endif">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </div>

                            @if(auth()->user()->role === 'partner')
                                <div class="alert alert-light small p-2 mb-3">
                                    <i class="fas fa-warehouse me-1"></i> 
                                    Mitra sejak {{ auth()->user()->created_at->format('M Y') }}
                                </div>
                            @endif

                            <div class="d-grid gap-2 mb-3">
                                <a href="{{ route('booking.index') }}" class="btn btn-outline-brand">
                                    <i class="fas fa-calendar-check me-1"></i> Riwayat Booking
                                </a>

                                @if(auth()->user()->role === 'customer')
                                    <a href="{{ route('partner.form') }}" class="btn btn-brand">
                                        <i class="fas fa-users me-1"></i> Gabung Jadi Mitra
                                    </a>
                                @endif
                            </div>

                            <!-- 🔥 SHORTCUT KHUSUS PARTNER (OPSI A) -->
                            @if(auth()->user()->role === 'partner')
                            <div class="mt-3 p-3 bg-light rounded-3 border small text-start">

                                <p class="fw-bold text-brand mb-2">
                                    <i class="fas fa-bolt me-1"></i> Shortcut Mitra
                                </p>

                                <div class="d-grid gap-2">

                                    <a href="{{ route('partner.dashboard') }}" class="btn btn-outline-brand btn-sm">
                                        <i class="fas fa-chart-line me-1"></i> Dashboard Mitra
                                    </a>

                                    <a href="{{ route('partner.lapangan.index') }}" class="btn btn-outline-brand btn-sm">
                                        <i class="fas fa-futbol me-1"></i> Kelola Lapangan
                                    </a>

                                    <a href="{{ route('partner.products.manage') }}" class="btn btn-outline-brand btn-sm">
                                        <i class="fas fa-store me-1"></i> Kelola Produk
                                    </a>

                                </div>
                            </div>
                            @endif
                            <!-- END SHORTCUT -->

                        </div>
                    </div>
                </div>

                <!-- Form Edit -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i> Edit Profil</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PATCH')

                                <!-- Nama -->
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Nama Lengkap</label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', auth()->user()->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Email</label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Password Baru <small class="text-muted">(opsional)</small></label>
                                    <input type="password" 
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Kosongkan jika tidak ingin ganti">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-brand py-2">
                                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger py-2"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteAccountModal">
                                        <i class="fas fa-trash-alt me-2"></i> Hapus Akun
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Akun -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i> Hapus Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">
                    <strong>Peringatan!</strong> Menghapus akun akan:
                </p>
                <ul class="small text-muted">
                    <li>Menghapus semua data pribadi</li>
                    <li>Membatalkan booking aktif</li>
                    <li>Menghapus akses ke lapangan / mitra</li>
                </ul>
                <p class="mt-2">
                    Ketik <code>{{ auth()->user()->email }}</code> untuk konfirmasi:
                </p>

                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="email" 
                           name="confirm_email" 
                           class="form-control form-control-sm mb-3"
                           required
                           placeholder="Ketik email untuk konfirmasi">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash-alt me-1"></i> Hapus Akun Saya
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
    const input = this.querySelector('[name="confirm_email"]');
    if (input.value !== '{{ auth()->user()->email }}') {
        e.preventDefault();
        alert('Email tidak sesuai. Silakan ketik email Anda dengan benar.');
        input.focus();
    }
});
</script>
@endpush

@endsection