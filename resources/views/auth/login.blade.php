@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-5">
                <h1 class="fw-bold text-brand display-5">Masuk</h1>
                <p class="text-muted">Masuk untuk melanjutkan booking lapanganmu!</p>
            </div>

            <!-- ✅ Session Status -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Email</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">Password</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me + Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted" for="remember">
                                    Ingat saya
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="small text-brand" href="{{ route('password.request') }}">
                                    <i class="fas fa-key me-1"></i> Lupa password?
                                </a>
                            @endif
                        </div>

                        <!-- Tombol Login -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-brand btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <span class="text-muted">Belum punya akun?</span>
                            <a href="{{ route('register') }}" class="text-brand fw-bold ms-1">Daftar Sekarang</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection