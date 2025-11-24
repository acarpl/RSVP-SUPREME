@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Buat Voucher Baru</h2>

    <form action="{{ route('partner.vouchers.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            {{-- Partner --}}
            <div class="col-md-6">
                <label class="form-label">Partner</label>
                <select name="partner_id" class="form-select" required>
                    @foreach($partners as $partner)
                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Kode Voucher --}}
            <div class="col-md-6">
                <label class="form-label">Kode Voucher</label>
                <input type="text" name="code" class="form-control" required>
            </div>

            {{-- Deskripsi --}}
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="2" required></textarea>
            </div>

            {{-- Tipe & Value (inline) --}}
            <div class="col-md-6">
                <label class="form-label">Tipe & Nilai</label>
                <div class="input-group">
                    <select name="type" class="form-select" style="max-width:120px" required>
                        <option value="percent">%</option>
                        <option value="fixed">Rp</option>
                    </select>
                    <input type="number" name="value" class="form-control" placeholder="Nilai" required>
                </div>
            </div>

            {{-- Minimal Belanja --}}
            <div class="col-md-6">
                <label class="form-label">Min. Belanja</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="min_amount" class="form-control" required>
                </div>
            </div>

            {{-- Maks Diskon (opsional, kecil) --}}
            <div class="col-md-6">
                <label class="form-label">Maks Diskon <small class="text-muted">(jika %)</small></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="max_discount" class="form-control" placeholder="Opsional">
                </div>
            </div>

            {{-- Quota & Expired --}}
            <div class="col-md-3">
                <label class="form-label">Quota</label>
                <input type="number" name="quota" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Berlaku Sampai</label>
                <input type="date" name="expires_at" class="form-control" required>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-brand px-4">💾 Simpan Voucher</button>
        </div>
    </form>
</div>
@endsection