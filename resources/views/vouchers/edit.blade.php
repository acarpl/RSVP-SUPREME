@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2>Edit Voucher</h2>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Kode Voucher</label>
                    <input type="text" class="form-control" value="{{ $voucher->code }}" disabled>
                </div>

                <div class="mb-3">
                    <label>Diskon (%)</label>
                    <input type="number" name="discount" class="form-control" value="{{ $voucher->discount }}" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal Kadaluarsa</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ $voucher->expiry_date }}" required>
                </div>

                <div class="mb-3">
                    <label>Quota</label>
                    <input type="number" name="quota" class="form-control" value="{{ $voucher->quota }}" required>
                </div>

                <button class="btn btn-warning">Update</button>
                <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>

</div>
@endsection
