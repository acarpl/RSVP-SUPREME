@extends('layouts.app')

@section('title', 'Tambah Lapangan')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <a href="{{ route('partner.lapangan.index') }}" class="inline-flex items-center text-blue-600 hover:underline mb-6">
        ← Kembali ke Daftar
    </a>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Lapangan Baru</h2>

        <form action="{{ route('partner.lapangan.store') }}" method="POST" id="lapanganForm">
            @csrf

            <!-- Nama -->
            <div class="mb-4">
                <label for="nama" class="block text-gray-700 font-medium mb-2">Nama Lapangan *</label>
                <input type="text" name="nama" id="nama" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('nama') }}">
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Lokasi -->
            <div class="mb-4">
                <label for="lokasi" class="block text-gray-700 font-medium mb-2">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('lokasi') }}">
            </div>

            <!-- Harga -->
            <div class="mb-4">
                <label for="harga" class="block text-gray-700 font-medium mb-2">Harga per Jam (Rp) *</label>
                <input type="number" name="harga" id="harga" required min="0"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('harga') }}">
            </div>

            <!-- Kapasitas -->
            <div class="mb-4">
                <label for="kapasitas" class="block text-gray-700 font-medium mb-2">Kapasitas (orang)</label>
                <input type="number" name="kapasitas" id="kapasitas" min="1"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('kapasitas') }}">
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Status *</label>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="aktif" required
                               class="text-blue-600 focus:ring-blue-500" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }}>
                        <span class="ml-2">Aktif (Tampil ke Customer)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="nonaktif"
                               class="text-gray-600 focus:ring-gray-500" {{ old('status') == 'nonaktif' ? 'checked' : '' }}>
                        <span class="ml-2">Nonaktif</span>
                    </label>
                </div>
            </div>

            <!-- Upload & Crop Gambar -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Foto Lapangan</label>
                <p class="text-sm text-gray-500 mb-3">Upload untuk preview & crop sesuai kebutuhan.</p>

                <!-- Upload asli -->
                <input type="file" id="imageUpload" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                <!-- Preview & Crop Area -->
                <div id="cropContainer" class="mt-4 hidden">
                    <img id="previewImage" src="" alt="Preview" class="hidden">
                    <div class="relative w-full h-64 bg-gray-100 rounded overflow-hidden mt-2">
                        <img id="cropImage" src="" alt="Crop Preview" class="absolute inset-0 w-full h-full object-contain">
                    </div>
                </div>

                <!-- Hidden field untuk base64 cropped image -->
                <input type="hidden" name="cropped_gambar" id="cropped_gambar">
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('partner.lapangan.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" id="submitBtn"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageUpload = document.getElementById('imageUpload');
    const previewImage = document.getElementById('previewImage');
    const cropImage = document.getElementById('cropImage');
    const cropContainer = document.getElementById('cropContainer');
    const croppedInput = document.getElementById('cropped_gambar');
    let cropper;

    imageUpload.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            const imgSrc = event.target.result;
            previewImage.src = imgSrc;
            cropImage.src = imgSrc;
            cropContainer.classList.remove('hidden');

            // Inisialisasi Cropper.js
            if (cropper) cropper.destroy();
            cropper = new Cropper(cropImage, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                autoCropArea: 0.8,
                movable: true,
                scalable: true,
                zoomable: true,
                guides: true,
            });
        };
        reader.readAsDataURL(file);
    });

    // Saat form submit, convert crop ke base64
    document.getElementById('lapanganForm').addEventListener('submit', function (e) {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 800,
                height: 450,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            croppedInput.value = canvas.toDataURL('image/jpeg', 0.9); // base64
        }
        // Tetap submit meskipun tidak ada gambar
    });
});
</script>
@endpush
@endsection