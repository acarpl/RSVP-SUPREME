@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <div class="bg-brand text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-lock fa-2x"></i>
                </div>
                <h2 class="fw-bold text-brand">Konfirmasi Password</h2>
                <p class="text-muted mt-2">
                    Ini area aman. Mohon konfirmasi password kamu sebelum melanjutkan.
                </p>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">Password</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   placeholder="Masukkan password kamu"
                                   required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-brand btn-lg">
                                <i class="fas fa-check-circle me-2"></i> Konfirmasi & Lanjutkan
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="small text-brand">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection