@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-brand text-white text-center py-4 rounded-top-4" style="background: linear-gradient(135deg, #0d6efd, #1666c5);">
                    <h3 class="mb-0 fw-bold">Gabung Menjadi Mitra</h3>
                    <small class="text-white-50">Daftarkan usaha lapangan olahraga Anda</small>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
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
                            <label class="form-label fw-semibold">Nama Usaha / Lapangan</label>
                            <input type="text" name="nama_usaha" class="form-control form-control-lg rounded-3" required value="{{ old('nama_usaha') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lokasi</label>
                            <textarea name="alamat_usaha" rows="3" class="form-control rounded-3" required>{{ old('alamat_usaha') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" name="telepon" class="form-control form-control-lg rounded-3" value="{{ old('telepon') }}">
                        </div>

                        <button class="btn btn-brand w-100 py-2 rounded-3 fw-semibold" style="font-size: 1.05rem;">
                            <i class="fas fa-paper-plane me-1"></i>
                            Ajukan Menjadi Mitra
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
