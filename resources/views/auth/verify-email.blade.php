@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-envelope-open-text fa-2x"></i>
                </div>
                <h2 class="fw-bold">Verifikasi Email</h2>
                <p class="text-muted mt-2">
                    Terima kasih sudah daftar!  
                    Mohon verifikasi emailmu dengan klik link yang telah kami kirim.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    Link verifikasi baru telah dikirim ke:
                    <strong>{{ auth()->user()->email }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-brand w-100">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Ulang Email Verifikasi
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-light w-100 text-muted">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3 text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Cek folder <strong>Spam/Promotions</strong> jika email tidak masuk.
            </div>
        </div>
    </div>
</div>
@endsection