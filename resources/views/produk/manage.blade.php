@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3>Kelola Produk</h3>
    <a href="{{ route('partner.products.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Stock</th>
            <th>Aksi</th>
        </tr>

        @foreach($products as $product)
        <tr>
            <td><img src="{{ asset('storage/'.$product->image) }}" width="70"></td>
            <td>{{ $product->name }}</td>
            <td>Rp {{ number_format($product->price) }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <a href="{{ route('partner.products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form class="d-inline" method="POST" action="{{ route('partner.products.destroy', $product->id) }}">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Hapus produk?')" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

</div>
@endsection
