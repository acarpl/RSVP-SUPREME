@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Checkout</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Data Diri -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Data Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" 
                                   value="{{ Auth::user()->phone ?? 'Belum diisi' }}" 
                                   disabled>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alamat & Pesanan -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Detail Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label required">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Contoh No. 123, RT/RW, Kel/Desa, Kecamatan, Kota, Provinsi, Kode Pos" required>{{ old('alamat') }}</textarea>
                            @error('alamat')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Kirim sore hari, dll">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Rincian Biaya</h5>
                    </div>
                    <div class="card-body">
                        @foreach($items as $item)
                            <div class="d-flex justify-content-between small">
                                <span>{{ $item['product']->name }} ×{{ $item['quantity'] }}</span>
                                <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('cart.index') }}" class="btn btn-secondary">← Kembali</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-credit-card"></i> Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection