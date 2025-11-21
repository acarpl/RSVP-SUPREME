@extends('layouts.app')

@section('title', 'Kelola Lapangan')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <a href="{{ route('partner.dashboard') }}" class="btn btn-outline-brand rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="fw-bold text-brand mb-1">
                <i class="fas fa-tools me-2"></i> Kelola Lapangan
            </h1>
            <p class="text-muted mb-0">
                {{ $lapangans->total() }} lapangan yang kamu kelola
            </p>
        </div>
        <a href="{{ route('partner.lapangan.create') }}" class="btn btn-brand px-4 py-2 mt-3 mt-md-0">
            <i class="fas fa-plus me-1"></i> Tambah Lapangan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabel Lapangan -->
    @if($lapangans->count())
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="30%">Nama & Lokasi</th>
                                <th width="10%" class="text-center">Kapasitas</th>
                                <th width="15%" class="text-end">Harga/Jam</th>
                                <th width="15%" class="text-center">Status</th>
                                <th width="25%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lapangans as $lapangan)
                                <tr>
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            @if($lapangan->gambar)
                                                <img src="{{ asset('storage/' . $lapangan->gambar) }}" 
                                                     class="rounded me-2" width="40" height="40" alt="{{ $lapangan->nama }}">
                                            @else
                                                <div class="bg-light border rounded me-2 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-futbol text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $lapangan->nama }}</div>
                                                <small class="text-muted">{{ Str::limit($lapangan->lokasi, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-light text-dark">{{ $lapangan->kapasitas ?? '-' }}</span>
                                    </td>
                                    <td class="text-end align-middle fw-bold text-primary">
                                        @if($lapangan->harga)
                                            Rp {{ number_format($lapangan->harga) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($lapangan->status == 'aktif')
                                            <span class="badge bg-success text-white">
                                                <i class="fas fa-check-circle me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary text-white">
                                                <i class="fas fa-pause-circle me-1"></i> Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('partner.lapangan.edit', $lapangan) }}" 
                                               class="btn btn-sm btn-outline-brand" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('partner.lapangan.destroy', $lapangan) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hapus"
                                                        onclick="return confirm('Yakin hapus {{ addslashes($lapangan->nama) }}?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $lapangans->links() }}
        </div>

    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                 style="width: 100px; height: 100px;">
                <i class="fas fa-futbol fa-3x text-muted"></i>
            </div>
            
            <h2 class="fw-bold text-muted mb-3">Belum Ada Lapangan</h2>
            <p class="text-muted mb-4 px-3">
                Tambahkan lapangan pertamamu dan mulai terima booking!
            </p>
            
            <a href="{{ route('partner.lapangan.create') }}" class="btn btn-brand px-4 py-2">
                <i class="fas fa-plus me-1"></i> Tambah Lapangan Pertama
            </a>
        </div>
    @endif
</div>
@endsection