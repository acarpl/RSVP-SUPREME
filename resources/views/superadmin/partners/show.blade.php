@extends('layouts.superadmin')

@section('content')
<div class="container py-5">
    <a href="{{ route('superadmin.partners.index') }}" class="d-inline-flex align-items-center text-decoration-none mb-4">
        <i class="fas fa-arrow-left text-primary me-1"></i>
        <span class="text-primary fw-medium">Kembali ke Daftar Partner</span>
    </a>

    <!-- Partner Info Card -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <h2 class="h4 card-title mb-2">{{ $partner->name }}</h2>
            <p class="text-muted mb-2">
                <i class="fas fa-envelope fa-sm me-1"></i> {{ $partner->email }}
            </p>
            <span class="badge bg-success">
                <i class="fas fa-user-tag me-1"></i> Role: {{ ucfirst($partner->role) }}
            </span>
        </div>
    </div>

<style>
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1) !important;
}
</style>
@endsection