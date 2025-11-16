@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-3">Daftar Voucher</h2>

    {{-- Hanya admin & partner yang boleh menambah voucher --}}
    @if(auth()->check() && (auth()->user()->role == 'admin' || auth()->user()->role == 'partner'))
        <a href="{{ route('vouchers.create') }}" class="btn btn-primary mb-3">Tambah Voucher</a>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Diskon</th>
                        <th>Kadaluarsa</th>
                        <th>Quota</th>
                        @if(auth()->check() && (auth()->user()->role == 'admin' || auth()->user()->role == 'partner'))
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($vouchers as $v)
                        <tr>
                            <td>{{ $v->code }}</td>
                            <td>{{ $v->discount }}%</td>
                            <td>{{ $v->expiry_date }}</td>
                            <td>{{ $v->quota }}</td>

                            @if(auth()->check() && (auth()->user()->role == 'admin' || auth()->user()->role == 'partner'))
                                <td>
                                    <a href="{{ route('vouchers.edit', $v->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('vouchers.destroy', $v->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Hapus voucher?')" class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection
