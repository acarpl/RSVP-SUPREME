@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Daftar Menjadi Mitra</h4>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('partner.register') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Usaha / Lapangan</label>
                            <input type="text" name="nama_usaha" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lokasi</label>
                            <textarea name="alamat" rows="3" class="form-control" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="telepon" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100">
                            Ajukan Menjadi Mitra
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
