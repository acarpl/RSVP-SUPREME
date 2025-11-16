@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">
        <h2 class="fw-bold">Daftar Lapangan</h2>

        @if(auth()->user()->role == 'partner' || auth()->user()->role == 'super_admin')
            <a href="{{ route('lapangan.create') }}" class="btn btn-primary">
                + Tambah Lapangan
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($lapangans as $lapangan)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">

                    @if($lapangan->gambar)
                        <img src="{{ asset('storage/'.$lapangan->gambar) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $lapangan->nama }}</h5>
                        <p class="text-muted mb-1">Lokasi: {{ $lapangan->lokasi }}</p>
                        <p class="mb-1">Kapasitas: {{ $lapangan->kapasitas }} orang</p>
                        <p class="fw-bold">Rp {{ number_format($lapangan->harga) }}/jam</p>

                        @if(auth()->user()->role == 'partner' || auth()->user()->role == 'super_admin')
                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('lapangan.edit', $lapangan) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('lapangan.destroy', $lapangan) }}" method="POST" 
                                      onsubmit="return confirm('Yakin hapus lapangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Belum ada lapangan.</p>
        @endforelse
    </div>

</div>
@endsection
