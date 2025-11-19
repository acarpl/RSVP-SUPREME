@extends('layouts.app')

@section('title', 'Kelola Lapangan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-brand">Kelola Lapangan</h1>
        <a href="{{ route('partner.lapangan.create') }}" class="btn btn-brand">
            <i class="fas fa-plus me-1"></i> Tambah Lapangan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Lokasi</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lapangans as $lapangan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($lapangan->gambar)
                                    <img src="{{ asset('storage/' . $lapangan->gambar) }}" 
                                         class="rounded me-2" width="40" height="40" alt="{{ $lapangan->nama }}">
                                @endif
                                {{ $lapangan->nama }}
                            </div>
                        </td>
                        <td>{{ $lapangan->lokasi ?? '-' }}</td>
                        <td>Rp {{ number_format($lapangan->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $lapangan->status == 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($lapangan->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('partner.lapangan.edit', $lapangan) }}" 
                               class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('partner.lapangan.destroy', $lapangan) }}" 
                                  method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Yakin hapus {{ $lapangan->nama }}?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada data lapangan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection