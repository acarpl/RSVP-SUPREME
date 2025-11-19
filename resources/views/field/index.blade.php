@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Daftar Lapangan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-800">Lapangan Tersedia</h1>
        <p class="text-gray-600 mt-2">Pilih dan pesan lapangan favorit Anda</p>
    </div>

    @if($lapangans->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Belum ada lapangan tersedia saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($lapangans as $lapangan)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    @if($lapangan->gambar)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $lapangan->gambar) }}"
                                 alt="{{ $lapangan->nama }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif

                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $lapangan->nama }}</h3>
                        <p class="text-gray-600 mt-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $lapangan->lokasi ?: 'Lokasi tidak tersedia' }}
                        </p>

                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-lg font-bold text-blue-600">
                                Rp{{ number_format($lapangan->harga, 0, ',', '.') }}/jam
                            </span>
                            @if($lapangan->kapasitas)
                                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded">
                                    {{ $lapangan->kapasitas }} orang
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('field.show', $lapangan) }}"
                           class="mt-4 w-full block text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Lihat Detail & Pesan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection