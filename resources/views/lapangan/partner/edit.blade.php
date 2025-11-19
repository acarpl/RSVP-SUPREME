@extends('layouts.app')

@section('title', 'Edit Lapangan - ' . $lapangan->nama)

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <a href="{{ route('partner.lapangan.index') }}" class="inline-flex items-center text-blue-600 hover:underline mb-6">
        ← Kembali ke Daftar
    </a>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Lapangan: {{ $lapangan->nama }}</h2>

        <form action="{{ route('partner.lapangan.update', $lapangan) }}" method="POST" id="lapanganForm">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div class="mb-4">
                <label for="nama" class="block text-gray-700 font-medium mb-2">Nama Lapangan *</label>
                <input type="text" name="nama" id="nama" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('nama', $lapangan->nama) }}">
                @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Lokasi -->
            <div class="mb-4">
                <label for="lokasi" class="block text-gray-700 font-medium mb-2">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('lokasi', $lapangan->lokasi) }}">
            </div>

            <!-- Harga -->
            <div class="mb-4">
                <label for="harga" class="block text-gray-700 font-medium mb-2">Harga per Jam (Rp) *</label>
                <input type="number" name="harga" id="harga" required min="0"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('harga', $lapangan->harga) }}">
            </div>

            <!-- Kapasitas -->
            <div class="mb-4">
                <label for="kapasitas" class="block text-gray-700 font-medium mb-2">Kapasitas (orang)</label>
                <input type="number" name="kapasitas" id="kapasitas" min="1"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('kapasitas', $lapangan->kapasitas) }}">
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Status *</label>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="aktif" required
                               class="text-blue-600 focus:ring-blue-500" {{ old('status', $lapangan->status) == 'aktif' ? 'checked' : '' }}>
                        <span class="ml-2">Aktif</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="nonaktif"
                               class="text-gray-600 focus:ring-gray-500" {{ old('status', $lapangan->status) == 'nonaktif' ? 'checked' : '' }}>
                        <span class="ml-2">Nonaktif</span>
                    </label>
                </div>
            </div>

            <!-- Gambar Saat Ini -->
            @if($lapangan->gambar)
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Foto Saat Ini</label>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $lapangan->gambar) }}"
                             alt="Current" class="h-32 rounded object-cover">
                    </div>
                </div>
            @endif

            <!-- Upload & Crop Baru -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Ganti Foto (Opsional)</label>
                <p class="text-sm text-gray-500 mb-3">Upload gambar baru untuk mengganti.</p>

                <input type="file" id="imageUpload" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                <div id="cropContainer" class="mt-4 hidden">
                    <img id="previewImage" src="" alt="Preview" class="hidden">
                    <div class="relative w-full h-64 bg-gray-100 rounded overflow-hidden mt-2">
                        <img id="cropImage" src="" alt="Crop Preview" class="absolute inset-0 w-full h-full object-contain">
                    </div>
                </div>

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
                    Perbarui
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
    const cropImage = document.getElementById('cropImage');
    const cropContainer = document.getElementById('cropContainer');
    const croppedInput = document.getElementById('cropped_gambar');
    let cropper;

    imageUpload.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            cropImage.src = event.target.result;
            cropContainer.classList.remove('hidden');

            if (cropper) cropper.destroy();
            cropper = new Cropper(cropImage, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                autoCropArea: 0.8,
            });
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('lapanganForm').addEventListener('submit', function () {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({ width: 800, height: 450 });
            croppedInput.value = canvas.toDataURL('image/jpeg', 0.9);
        }
    });
});
</script>
@endpush
@endsection