@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">📊 Dashboard Super Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500">
                <h3 class="text-gray-500 text-sm font-semibold">TOTAL PENGGUNA</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500">
                <h3 class="text-gray-500 text-sm font-semibold">PARTNER</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPartners }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-purple-500">
                <h3 class="text-gray-500 text-sm font-semibold">CUSTOMER</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalCustomers }}</p>
            </div>
        </div>

        <div class="space-y-4">
            <a href="{{ route('superadmin.users.index') }}" class="block bg-white p-5 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">👥 Kelola Pengguna (Customer)</h3>
                        <p class="text-gray-600 text-sm">Edit/hapus akun customer biasa.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('superadmin.partners.index') }}" class="block bg-white p-5 rounded-xl shadow hover:shadow-lg transition transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">🏢 Kelola Partner</h3>
                        <p class="text-gray-600 text-sm">Lihat daftar partner & lapangan mereka.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection