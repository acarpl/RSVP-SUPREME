@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6">
    <a href="{{ route('superadmin.users.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>
    <h1 class="text-2xl font-bold mb-6">✏️ Edit Pengguna</h1>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('superadmin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full px-4 py-2 border rounded focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full px-4 py-2 border rounded focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Role</label>
            <select name="role" class="w-full px-4 py-2 border rounded focus:ring-blue-500 focus:border-blue-500">
                <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="partner" {{ old('role', $user->role) === 'partner' ? 'selected' : '' }}>Partner</option>
            </select>
            <p class="text-sm text-gray-500 mt-1">⚠️ Jangan ubah ke super_admin via sini.</p>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            ✅ Simpan Perubahan
        </button>
    </form>
</div>
@endsection