@extends('layouts.app')

@section('content')

<div class="container py-4">
    <h3 class="mb-4 fw-bold">Alat tempur perut</h3>

    <div class="row">
        @foreach($products as $p)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3">
                <h5 class="fw-bold">{{ $p->name }}</h5>
                <p>{{ $p->description }}</p>
                <strong>Rp{{ number_format($p->price, 0, ',', '.') }}</strong>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
