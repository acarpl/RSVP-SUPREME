@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-brand rounded-circle me-3" title="Kembali">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="fw-bold text-brand mb-1">Order Sekarang</h1>
                    <p class="text-muted mb-0">
                        Booking instan untuk 
                        <span class="fw-bold">{{ $lapangan->nama ?? 'Lapangan' }}</span>
                    </p>
                </div>
            </div>

            <!-- Card Utama -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-4">
                    <!-- Info Lapangan -->
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-futbol text-brand fa-2x"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $lapangan->nama ?? 'Nama Lapangan' }}</h5>
                            <div class="d-flex flex-column flex-md-row gap-3">
                                <span class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i> 
                                    {{ $lapangan->lokasi ?? 'Lokasi tidak tersedia' }}
                                </span>
                                <span class="text-primary fw-bold">
                                    Rp {{ number_format($lapangan->price_per_hour ?? 0) }}/jam
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Booking -->
                    <form method="POST" action="{{ route('booking.store-order-now', $lapangan->id) }}">
                        @csrf

                        <!-- Waktu Booking -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Jadwal Booking</label>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small text-muted">Tanggal & Jam Mulai</label>
                                    <input type="datetime-local" 
                                           name="start_time" 
                                           class="form-control form-control-lg @error('start_time') is-invalid @enderror"
                                           required
                                           value="{{ old('start_time', \Carbon\Carbon::now()->addHour()->format('Y-m-d\TH:i')) }}"
                                           min="{{ \Carbon\Carbon::now()->addHour()->format('Y-m-d\TH:i') }}">
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label small text-muted">Durasi</label>
                                    <select name="duration_hours" 
                                            id="durationSelect"
                                            class="form-select form-select-lg @error('duration_hours') is-invalid @enderror"
                                            required>
                                        <option value="">Pilih durasi</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('duration_hours') == $i ? 'selected' : '' }}>
                                                {{ $i }} jam
                                            </option>
                                        @endfor
                                    </select>
                                    @error('duration_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Produk Tambahan -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">
                                Produk Tambahan 
                                <small class="text-muted">(Opsional)</small>
                            </label>
                            <div class="row g-3">
                                @forelse($products as $product)
                                    <div class="col-6 col-md-4">
                                        <div class="card border h-100">
                                            <div class="position-relative">
                                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/100x100/e2e8f0/94a3b8?text=+' }}"
                                                     class="card-img-top" 
                                                     alt="{{ $product->name }}"
                                                     style="height: 100px; object-fit: cover;">
                                                
                                                @if($product->stock > 0 && $product->stock <= 5)
                                                    <span class="position-absolute top-0 end-0 bg-warning text-dark fw-bold px-1 py-0 small">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> {{ $product->stock }}
                                                    </span>
                                                @elseif($product->stock == 0)
                                                    <span class="position-absolute top-0 end-0 bg-danger text-white fw-bold px-1 py-0 small">
                                                        <i class="fas fa-times me-1"></i> Habis
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="card-body p-2">
                                                <h6 class="card-title small mb-1">{{ Str::limit($product->name, 20) }}</h6>
                                                <p class="card-text small text-primary fw-bold mb-1">
                                                    Rp {{ number_format($product->price) }}
                                                </p>
                                                <div class="form-check small">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           id="prod_{{ $product->id }}"
                                                           {{ old("products.{$product->id}") ? 'checked' : '' }}
                                                           {{ $product->stock == 0 ? 'disabled' : '' }}
                                                           onchange="toggleProduct({{ $product->id }}, this.checked, '{{ addslashes($product->name) }}', {{ $product->price }})">
                                                    <label class="form-check-label" for="prod_{{ $product->id }}">
                                                        <small>
                                                            {{ $product->stock == 0 ? 'Stok habis' : 'Tambahkan' }}
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-light text-center mb-0 py-3">
                                            <i class="fas fa-box-open text-muted fa-2x mb-2"></i>
                                            <p class="mb-0">Belum ada produk tersedia</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Ringkasan -->
                        <div class="border-top pt-3">
                            <h5 class="mb-3">Ringkasan Pesanan</h5>
                            
                            {{-- Item Lapangan --}}
                            <input type="hidden" name="items[0][type]" value="lapangan">
                            <input type="hidden" name="items[0][id]" value="{{ $lapangan->id }}">
                            <input type="hidden" name="items[0][name]" value="{{ $lapangan->nama }}">
                            <input type="hidden" name="items[0][price]" id="lapanganPriceInput" value="{{ $lapangan->price_per_hour ?? 0 }}">
                            <input type="hidden" name="items[0][quantity]" id="durationInput" value="{{ old('duration_hours', 1) }}">

                            {{-- Item Produk --}}
                            <div id="productItems">
                                @if(old('products'))
                                    @foreach(old('products') as $id => $checked)
                                        @php
                                            $product = $products->firstWhere('id', $id);
                                        @endphp
                                        @if($product)
                                            <input type="hidden" name="items[{{ $loop->index + 1 }}][type]" value="product">
                                            <input type="hidden" name="items[{{ $loop->index + 1 }}][id]" value="{{ $product->id }}">
                                            <input type="hidden" name="items[{{ $loop->index + 1 }}][name]" value="{{ $product->name }}">
                                            <input type="hidden" name="items[{{ $loop->index + 1 }}][price]" value="{{ $product->price }}">
                                            <input type="hidden" name="items[{{ $loop->index + 1 }}][quantity]" value="1">
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>
                                    Lapangan (<span id="durationText">{{ old('duration_hours', 1) }}</span> jam)
                                </span>
                                <span id="lapanganTotal" class="fw-bold">
                                    Rp {{ number_format(($lapangan->price_per_hour ?? 0) * (old('duration_hours', 1))) }}
                                </span>
                            </div>
                            <div id="productSummary">
                                @if(old('products'))
                                    @foreach(old('products') as $id => $checked)
                                        @php $product = $products->firstWhere('id', $id); @endphp
                                        @if($product)
                                            <div class="d-flex justify-content-between mb-1" id="summary_{{ $product->id }}">
                                                <span>{{ $product->name }}</span>
                                                <span>Rp {{ number_format($product->price) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total:</span>
                                <span id="grandTotal">
                                    Rp {{ number_format(
                                        ($lapangan->price_per_hour ?? 0) * (old('duration_hours', 1)) +
                                        ($products->filter(fn($p) => old("products.{$p->id}"))->sum('price') ?? 0)
                                    ) }}
                                </span>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-brand w-100 py-3">
                                <i class="fas fa-bolt me-2"></i> BOOKING SEKARANG
                            </button>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i> 
                                    Pembayaran aman via Midtrans • Batal kapan saja
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips -->
            <div class="alert alert-light border-0 rounded-3 mt-4">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3 mt-1">
                        <i class="fas fa-lightbulb text-brand fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Tips Sportykuy</h6>
                        <ul class="mb-0 small text-muted">
                            <li>Booking minimal 1 jam sebelum jadwal</li>
                            <li>Gratis antar-jemput untuk durasi > 3 jam</li>
                            <li>Pembatalan 2 jam sebelum jadwal → refund 100%</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lapanganPrice = {{ $lapangan->price_per_hour ?? 0 }};
    let productIndex = {{ old('products') ? count(old('products')) + 1 : 1 }};
    let grandTotal = lapanganPrice * (parseInt(document.getElementById('durationSelect').value) || 1);

    function updateGrandTotal() {
        document.getElementById('grandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    function toggleProduct(id, checked, name, price) {
        const container = document.getElementById('productItems');
        const summary = document.getElementById('productSummary');
        
        if (checked) {
            // Tambah input hidden
            const input = `
                <input type="hidden" name="items[${productIndex}][type]" value="product">
                <input type="hidden" name="items[${productIndex}][id]" value="${id}">
                <input type="hidden" name="items[${productIndex}][name]" value="${name}">
                <input type="hidden" name="items[${productIndex}][price]" value="${price}">
                <input type="hidden" name="items[${productIndex}][quantity]" value="1">
            `;
            container.insertAdjacentHTML('beforeend', input);
            
            // Tambah ke summary
            const summaryLine = `
                <div class="d-flex justify-content-between mb-1" id="summary_${id}">
                    <span>${name}</span>
                    <span>Rp ${price.toLocaleString('id-ID')}</span>
                </div>
            `;
            summary.insertAdjacentHTML('beforeend', summaryLine);
            
            grandTotal += price;
            productIndex++;
        } else {
            // Hapus dari summary
            const summaryLine = document.getElementById(`summary_${id}`);
            if (summaryLine) summaryLine.remove();
            
            // Kurangi total
            grandTotal -= price;
        }
        
        updateGrandTotal();
    }

    // Update saat durasi berubah
    document.getElementById('durationSelect').addEventListener('change', function() {
        const duration = parseInt(this.value) || 1;
        const newLapanganTotal = lapanganPrice * duration;
        
        // Update tampilan
        document.getElementById('durationText').textContent = duration;
        document.getElementById('lapanganTotal').textContent = 'Rp ' + newLapanganTotal.toLocaleString('id-ID');
        document.getElementById('durationInput').value = duration;
        
        // Hitung ulang total
        const productTotals = Array.from(document.querySelectorAll('#productSummary .d-flex'))
            .map(el => {
                const text = el.querySelector('span:last-child').textContent;
                return parseInt(text.replace(/\D/g, '')) || 0;
            })
            .reduce((a, b) => a + b, 0);
        
        grandTotal = newLapanganTotal + productTotals;
        updateGrandTotal();
    });

    // Inisialisasi total
    updateGrandTotal();
});
</script>
@endpush
@endsection