<div class="field-card mb-4">

    <img src="{{ $image ?? 'https://placehold.co/400x250/FF7A3F/FFFFFF?text=Lapangan' }}" 
         class="w-100"
         style="height: 200px; object-fit: cover;"
         alt="{{ $title ?? 'Lapangan' }}">

    <div class="p-3">
        <h5 class="fw-bold mb-2">{{ $title ?? 'Nama Lapangan' }}</h5>

        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-map-marker-alt text-muted me-1"></i>
            <small class="text-muted">{{ $location ?? 'Lokasi tidak tersedia' }}</small>
        </div>

        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-users text-muted me-1"></i>
            <small class="text-muted">{{ $capacity ?? 'Kapasitas tidak tersedia' }}</small>
        </div>

        <div class="d-flex align-items-center mb-3">
            <i class="fas fa-tag text-primary me-1"></i>
            <span class="fw-bold text-primary">
                {{ $price ?? 'Harga tidak tersedia' }}
            </span>
        </div>

        {{-- Slot --}}
        @if(trim($slot) != '')
            {{ $slot }}
        @else
            <a href="{{ $url ?? '#' }}" class="btn btn-sm btn-outline-brand w-100">
                Detail & Booking
            </a>
        @endif
    </div>
</div>
