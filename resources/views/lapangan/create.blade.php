@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Tambah Lapangan</h2>

    <form action="{{ route('lapangan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Lapangan</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kapasitas</label>
            <input type="number" name="kapasitas" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga / Jam</label>
            <input type="number" name="harga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" name="gambar" class="form-control">
        </div>

        <button class="btn btn-primary">Tambah</button>
    </form>
</div>
@endsection
