@extends('layouts.app')

@section('title', 'Detail Lapangan - ' . $lapangan->nama)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <a href="{{ route('lapangan.index') }}" class="inline-flex items-center text-blue-600 hover:underline mb-6">
        ← Kembali ke Daftar
    </a>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if($lapangan->gambar)
            <div class="h-64 md:h-80 overflow-hidden">
                <img src="{{ asset('storage/' . $lapangan->gambar) }}" 
                     alt="{{ $lapangan->nama }}"
                     class="w-full h-full object-cover">
            </div>
        @else
            <div class="h-64 md:h-80 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500 text-xl">No Image</span>
            </div>
        @endif

        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $lapangan->nama }}</h1>
                    <p class="text-gray-600 mt-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $lapangan->lokasi }}
                    </p>
                </div>
                <span class="mt-3 md:mt-0 bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">
                    Rp{{ number_format($lapangan->harga, 0, ',', '.') }}/jam
                </span>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Kapasitas:</span>
                    <p class="font-medium">{{ $lapangan->kapasitas ?? '-' }} orang</p>
                </div>
                <div>
                    <span class="text-gray-500">Status:</span>
                    <p class="font-medium text-green-600">Tersedia</p>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Pesan Sekarang</h2>
                <form action="#" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="tanggal" required
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Jam Mulai</label>
                            <input type="time" name="jam_mulai" required
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-700 mb-2">Durasi (jam)</label>
                        <select name="durasi" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="1">1 jam</option>
                            <option value="2">2 jam</option>
                            <option value="3">3 jam</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition">
                        Lanjutkan ke Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection