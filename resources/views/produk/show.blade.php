@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-5">
            <img src="{{ asset('storage/'.$product->image) }}" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-7">
            <h2>{{ $product->name }}</h2>
            <h4 class="text-success">Rp {{ number_format($product->price) }}</h4>
            <p>{{ $product->description }}</p>
            <button class="btn btn-success btn-lg">Beli Sekarang</button>
        </div>
    </div>
</div>
@endsection
