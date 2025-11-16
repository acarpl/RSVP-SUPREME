@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4">Edit Lapangan</h2>

    <form action="{{ route('lapangan.update', $lapangan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-3">
            <label class="form-label">Nama Lapangan</label>
            <input type="text" name="nama" value="{{ old('nama', $lapangan->nama) }}"
                   class="form-control @error('nama') is-invalid @enderror">
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Lokasi --}}
        <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" name="lokasi" value="{{ old('lokasi', $lapangan->lokasi) }}"
                   class="form-control @error('lokasi') is-invalid @enderror">
            @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Kapasitas --}}
        <div class="mb-3">
            <label class="form-label">Kapasitas</label>
            <input type="number" name="kapasitas" value="{{ old('kapasitas', $lapangan->kapasitas) }}"
                   class="form-control @error('kapasitas') is-invalid @enderror">
            @error('kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Harga --}}
        <div class="mb-3">
            <label class="form-label">Harga (per jam)</label>
            <input type="number" name="harga" value="{{ old('harga', $lapangan->harga) }}"
                   class="form-control @error('harga') is-invalid @enderror">
            @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Upload + Crop --}}
        <div class="mb-3">
            <label class="form-label">Gambar Lapangan</label>
            <input type="file" id="uploadImage" accept="image/*"
                class="form-control @error('gambar') is-invalid @enderror">

            @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror

            {{-- Preview --}}
            <div class="mt-3">
                <img id="previewImage"
                     src="{{ $lapangan->gambar ? asset('storage/' . $lapangan->gambar) : '' }}"
                     class="rounded border p-1"
                     style="max-width:200px; display: {{ $lapangan->gambar ? 'block' : 'none' }};">
            </div>

            {{-- Tombol Hapus --}}
            <button type="button" id="removeImage"
                class="btn btn-danger btn-sm mt-2"
                style="display: {{ $lapangan->gambar ? 'inline-block' : 'none' }};">
                Hapus Gambar
            </button>

            {{-- Input Hidden Untuk Hasil Crop --}}
            <input type="hidden" name="cropped_gambar" id="croppedImage">
        </div>

        <button class="btn btn-primary mt-3">Update</button>
    </form>
</div>

{{-- MODAL CROPPING --}}
<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <h5 class="mb-2">Crop Gambar</h5>
            <div id="cropContainer"></div>

            <div class="text-end mt-3">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="cropButton">Crop</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let croppie = null;
let cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

document.getElementById('uploadImage').addEventListener('change', function (event) {
    let file = event.target.files[0];
    if (!file) return;

    let reader = new FileReader();
    reader.onload = function (e) {
        // Tampilkan modal crop
        cropModal.show();

        // Hapus instansi croppie sebelumnya
        if (croppie) croppie.destroy();

        // Inisialisasi croppie
        croppie = new Croppie(document.getElementById('cropContainer'), {
            viewport: { width: 250, height: 250 },
            boundary: { width: 300, height: 300 },
            enableZoom: true
        });

        croppie.bind({ url: e.target.result });
    };

    reader.readAsDataURL(file);
});

// Tombol Crop
document.getElementById('cropButton').addEventListener('click', function () {
    croppie.result({ type: 'base64', size: 'viewport' }).then(function (img) {

        // Set hasil crop ke hidden input
        document.getElementById('croppedImage').value = img;

        // Preview gambar
        let preview = document.getElementById('previewImage');
        preview.src = img;
        preview.style.display = "block";

        // Tampilkan tombol hapus
        document.getElementById('removeImage').style.display = "inline-block";

        cropModal.hide();
    });
});

// Hapus gambar
document.getElementById('removeImage').addEventListener('click', function () {
    document.getElementById('previewImage').style.display = "none";
    document.getElementById('previewImage').src = "";

    document.getElementById('croppedImage').value = "";
    this.style.display = "none";
});
</script>
@endsection
