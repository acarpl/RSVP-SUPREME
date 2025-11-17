@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Buat Voucher Baru</h2>

    <form action="{{ route('vouchers.store') }}" method="POST">
        @csrf

        {{-- PARTNER ID --}}
        <div class="mb-3">
            <label class="form-label">Partner</label>
            <select name="partner_id" class="form-control" required>
                @foreach($partners as $partner)
                    <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- CODE --}}
        <div class="mb-3">
            <label class="form-label">Kode Voucher</label>
            <input type="text" name="code" class="form-control" required>
        </div>

        {{-- DESCRIPTION --}}
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        {{-- TYPE --}}
        <div class="mb-3">
            <label class="form-label">Tipe Voucher</label>
            <select name="type" class="form-control" required>
                <option value="percent">Percent</option>
                <option value="fixed">Fixed</option>
            </select>
        </div>

        {{-- VALUE --}}
        <div class="mb-3">
            <label class="form-label">Value</label>
            <input type="number" name="value" class="form-control" required>
        </div>

        {{-- MIN AMOUNT --}}
        <div class="mb-3">
            <label class="form-label">Minimal Belanja</label>
            <input type="number" name="min_amount" class="form-control" required>
        </div>

        {{-- MAX DISCOUNT (khusus percent) --}}
        <div class="mb-3">
            <label class="form-label">Maksimal Diskon (untuk percent)</label>
            <input type="number" name="max_discount" class="form-control">
        </div>

        {{-- QUOTA --}}
        <div class="mb-3">
            <label class="form-label">Quota</label>
            <input type="number" name="quota" class="form-control" required>
        </div>

        {{-- EXPIRES AT --}}
        <div class="mb-3">
            <label class="form-label">Expired</label>
            <input type="date" name="expires_at" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-brand px-4 mt-3">Simpan</button>
    </form>
</div>
@endsection
