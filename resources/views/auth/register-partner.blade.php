@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-3">Daftar Sebagai Partner</h1>
    <p class="text-muted mb-4">Daftarkan akun untuk mengelola lapangan dan menerima booking.</p>

    <form action="{{ route('partner.register.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>

        <button class="btn btn-brand">Daftar Partner</button>
    </form>
</div>
@endsection
