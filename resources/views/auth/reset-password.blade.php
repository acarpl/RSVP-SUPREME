@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <div class="bg-brand text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-key fa-2x"></i>
                </div>
                <h2 class="fw-bold text-brand">Atur Ulang Password</h2>
                <p class="text-muted">
                    Masukkan password baru untuk akunmu.
                </p>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Hidden Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Email</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $request->email) }}"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   readonly>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">Password Baru</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   placeholder="Minimal 8 karakter"
                                   required autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password</label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control form-control-lg"
                                   placeholder="Ulangi password"
                                   required autocomplete="new-password">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-brand btn-lg">
                                <i class="fas fa-lock me-2"></i> Simpan Password Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection