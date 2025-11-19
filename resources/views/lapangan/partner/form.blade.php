<div class="mb-3">
    <label class="form-label">Nama Lapangan *</label>
    <input type="text" name="nama" class="form-control" 
           value="{{ old('nama', $lapangan->nama ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Lokasi</label>
    <input type="text" name="lokasi" class="form-control" 
           value="{{ old('lokasi', $lapangan->lokasi ?? '') }}">
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Harga per Jam (Rp) *</label>
        <input type="number" name="harga" class="form-control" 
               value="{{ old('harga', $lapangan->harga ?? '') }}" min="0" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Kapasitas (orang)</label>
        <input type="number" name="kapasitas" class="form-control" 
               value="{{ old('kapasitas', $lapangan->kapasitas ?? '') }}" min="1">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Status *</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" id="aktif" value="aktif"
               {{ (old('status', $lapangan->status ?? 'aktif') == 'aktif') ? 'checked' : '' }} required>
        <label class="form-check-label" for="aktif">Aktif (Tampil ke Customer)</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" id="nonaktif" value="nonaktif"
               {{ (old('status', $lapangan->status ?? 'aktif') == 'nonaktif') ? 'checked' : '' }}>
        <label class="form-check-label" for="nonaktif">Nonaktif</label>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Foto Lapangan</label>
    <input type="file" name="gambar" class="form-control" accept="image/*">
    @if(isset($lapangan) && $lapangan->gambar)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $lapangan->gambar) }}" 
                 alt="Preview" class="img-thumbnail" width="150">
        </div>
    @endif
</div>