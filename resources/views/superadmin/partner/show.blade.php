@extends('layouts.superadmin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <a href="{{ route('superadmin.partners.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali ke Daftar Partner</a>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-2xl font-bold text-gray-800">{{ $partner->name }}</h2>
        <p class="text-gray-600">{{ $partner->email }}</p>
        <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Role: {{ $partner->role }}</span>
    </div>

    <h3 class="text-xl font-semibold mb-4">🏟️ Lapangan Milik Partner Ini</h3>

    @if($lapangan->isEmpty())
        <p class="text-gray-500">Belum ada lapangan.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($lapangan as $l)
            <div class="border rounded-lg p-4 hover:shadow-md">
                <h4 class="font-bold text-lg">{{ $l->nama_lapangan }}</h4>
                <p class="text-gray-600 text-sm">{{ $l->alamat }}</p>
                <p class="mt-2">
                    <span class="text-sm px-2 py-1 bg-blue-100 text-blue-800 rounded">Harga: Rp{{ number_format($l->harga_per_jam, 0, ',', '.') }}/jam</span>
                </p>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection