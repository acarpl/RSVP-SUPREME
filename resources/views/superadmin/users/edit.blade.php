@extends('layouts.superadmin')

@section('content')
<div class="container py-5">
    <a href="{{ route('superadmin.users.index') }}" class="d-inline-flex align-items-center text-decoration-none mb-4">
        <i class="fas fa-arrow-left text-primary me-1"></i>
        <span class="text-primary fw-medium">Kembali ke Daftar Pengguna</span>
    </a>

    <div class="card shadow-sm">
        <div class="card-header bg-white border-0 pb-0">
            <h1 class="h4 fw-bold text-primary">
                <i class="fas fa-user-edit me-2"></i>Edit Pengguna
            </h1>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-circle me-1"></i> Ada kesalahan input:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('superadmin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-medium">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="role" class="form-label fw-medium">Role</label>
                    <select
                        id="role"
                        name="role"
                        class="form-select @error('role') is-invalid @enderror"
                    >
                        <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>
                            <i class="fas fa-user me-1"></i> Customer
                        </option>
                        <option value="partner" {{ old('role', $user->role) == 'partner' ? 'selected' : '' }}>
                            <i class="fas fa-handshake me-1"></i> Partner
                        </option>
                    </select>
                    <div class="form-text text-muted">
                        <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                        Pengguna dengan role <code>super_admin</code> tidak boleh diubah melalui form ini.
                    </div>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection