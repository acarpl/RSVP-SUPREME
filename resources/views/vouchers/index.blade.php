@extends('layouts.app')

@section('title', 'Kelola Voucher')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('partner.dashboard') }}" class="btn btn-outline-brand rounded-circle me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="fw-bold text-brand d-inline">
                <i class="fas fa-ticket-alt me-2"></i> Kelola Voucher
            </h1>
        </div>
        <a href="{{ route('partner.vouchers.create') }}" class="btn btn-brand px-4 py-2">
            <i class="fas fa-plus me-1"></i> Tambah Voucher
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($vouchers->count())
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Nama & Gambar</th>
                                <th>Kode</th>
                                <th>Diskon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $voucher)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($voucher->image)
                                                <img src="{{ asset('storage/' . $voucher->image) }}"
                                                     alt="{{ $voucher->name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-ticket-alt text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $voucher->name }}</strong><br>
                                                <small class="text-muted">{{ Str::limit($voucher->description, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $voucher->code }}</span>
                                    </td>
                                    <td>
                                        @if($voucher->discount_type === 'percentage')
                                            <span class="badge bg-warning">-{{ $voucher->discount_value }}%</span>
                                        @else
                                            <span class="badge bg-warning">-Rp {{ number_format($voucher->discount_value) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $voucher->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $voucher->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('partner.vouchers.edit', $voucher) }}"
                                               class="btn btn-outline-brand btn-sm"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('partner.vouchers.destroy', $voucher) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin hapus {{ addslashes($voucher->name) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-sm"
                                                        title="Hapus">
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
    @else
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                 style="width: 100px; height: 100px;">
                <i class="fas fa-ticket-alt fa-3x text-muted"></i>
            </div>
            <h2 class="fw-bold text-muted mb-3">Belum Ada Voucher</h2>
            <p class="text-muted mb-4 px-3">
                Tambahkan voucher pertama untuk meningkatkan penjualan dan menarik lebih banyak pelanggan!
            </p>
            <a href="{{ route('partner.vouchers.create') }}" class="btn btn-brand px-4 py-2">
                <i class="fas fa-plus me-1"></i> Tambah Voucher Pertama
            </a>
        </div>
    @endif
</div>
@endsection