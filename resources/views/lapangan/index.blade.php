@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="fw-bold text-brand mb-2">Daftar Lapangan</h1>
            <p class="text-muted mb-0">{{ $lapangans->count() }} lapangan tersedia</p>
        </div>

        {{-- ✅ Tampilkan tombol hanya untuk partner/superadmin yang sudah login --}}
        @auth
            @if(in_array(auth()->user()->role, ['partner', 'super_admin']))
                <a href="{{ route('lapangan.create') }}" class="btn btn-brand px-4 py-2 mt-3 mt-md-0">
                    <i class="fas fa-plus me-1"></i> Tambah Lapangan
                </a>
            @endif
        @endauth
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($lapangans->count())
        <div class="row g-4">
            @foreach($lapangans as $lapangan)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                        <!-- Gambar Lapangan -->
                        <div style="height: 200px; background: #f1f5f9;">
                            @if($lapangan->gambar)
                                <img src="{{ asset('storage/' . $lapangan->gambar) }}"
                                     alt="{{ $lapangan->nama }}"
                                     class="w-100 h-100"
                                     style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-futbol fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Konten -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $lapangan->nama }}</h5>
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $lapangan->lokasi }}
                            </p>
                            <p class="card-text mb-1">
                                <i class="fas fa-users me-1 text-muted"></i> 
                                {{ $lapangan->kapasitas }} orang
                            </p>
                            <p class="card-text fw-bold text-primary mb-3">
                                <i class="fas fa-tag me-1"></i> 
                                Rp {{ number_format($lapangan->harga ?? 0) }}/jam
                            </p>

                            <div class="mt-auto">
                                <!-- Tombol Aksi -->
                                <div class="d-grid gap-2 d-md-block">
                                    <a href="{{ route('booking.order-now', $lapangan->id) }}" 
                                       class="btn btn-brand w-100 mb-2">
                                        <i class="fas fa-bolt me-1"></i> Order Sekarang
                                    </a>

                                    @auth
                                        @if(in_array(auth()->user()->role, ['partner', 'super_admin']))
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('lapangan.edit', $lapangan) }}" 
                                                   class="btn btn-outline-brand flex-fill">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('lapangan.destroy', $lapangan) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Yakin hapus {{ addslashes($lapangan->nama) }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger flex-fill">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($lapangans instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $lapangans->links() }}
@endif
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                 style="width: 80px; height: 80px;">
                <i class="fas fa-futbol fa-2x text-muted"></i>
            </div>
            <h3 class="fw-bold text-muted mb-2">Belum Ada Lapangan</h3>
            <p class="text-muted mb-4">
                @auth
                    @if(in_array(auth()->user()->role, ['partner', 'super_admin']))
                        Tambahkan lapangan pertamamu sekarang.
                    @else
                        Lapangan akan segera tersedia.
                    @endif
                @else
                    Login sebagai mitra untuk menambahkan lapangan.
                @endauth
            </p>

            @auth
                @if(in_array(auth()->user()->role, ['partner', 'super_admin']))
                    <a href="{{ route('lapangan.create') }}" class="btn btn-brand px-4 py-2">
                        <i class="fas fa-plus me-1"></i> Tambah Lapangan
                    </a>
                @endif
            @endauth
        </div>
    @endif
</div>
@endsection