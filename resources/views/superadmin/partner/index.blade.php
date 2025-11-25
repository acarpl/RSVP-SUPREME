@extends('layouts.superadmin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">🏢 Daftar Partner</h1>
        <a href="{{ route('superadmin.dashboard') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lapangan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($partners as $partner)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $partner->name }}</td>
                    <td class="px-4 py-3">{{ $partner->email }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                            {{ $partner->lapangan_count }} lapangan
                        </span>
                    </td>
                    <td class="px-4 py-3 space-x-2">
                        <a href="{{ route('superadmin.partners.show', $partner) }}" class="text-blue-600 hover:underline text-sm">Detail</a>
                        <form method="POST" action="{{ route('superadmin.partners.suspend', $partner) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-orange-600 hover:underline text-sm"
                                onclick="return confirm('Nonaktifkan partner ini? Mereka jadi customer biasa.')">
                                ⏸️ Nonaktifkan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada partner.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $partners->links() }}
    </div>
</div>
@endsection