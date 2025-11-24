@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-3">Daftar Voucher</h2>

    {{-- Admin & Partner boleh tambah --}}
    @if(auth()->check() && in_array(auth()->user()->role, ['admin','partner']))
        <a href="{{ route('partner.vouchers.create') }}" class="btn btn-primary mb-3">Tambah Voucher</a>
    @endif

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tipe</th>
                        <th>Nilai</th>
                        <th>Quota</th>
                        <th>Expired</th>

                        @if(auth()->check() && in_array(auth()->user()->role, ['admin','partner']))
                            <th>Aksi</th>
                        @else
                            <th>Pakai</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($vouchers as $v)
                        <tr>
                            <td>{{ $v->code }}</td>
                            <td>{{ $v->type }}</td>
                            <td>{{ $v->value }}</td>
                            <td>{{ $v->quota }}</td>
                            <td>{{ $v->expires_at }}</td>

                            {{-- Admin + Partner --}}
                            @if(auth()->check() && in_array(auth()->user()->role, ['admin','partner']))
                                <td>
                                    <a href="{{ route('partner.vouchers.edit', $v) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('partner.vouchers.destroy', $v) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus voucher?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            @else
                                {{-- USER --}}
                                <td>
                                    <form action="{{ route('vouchers.use', $v) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm">
                                            Pakai
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
