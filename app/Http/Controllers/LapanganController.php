<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LapanganController extends Controller
{
    // ===============
    // CUSTOMER/GUEST
    // ===============

    public function customerIndex()
    {
        $lapangans = Lapangan::where('status', 'aktif')
                             ->latest()
                             ->paginate(9); // ✅ pagination

        return view('lapangan.index', compact('lapangans'));
    }

    public function customerShow(Lapangan $lapangan)
    {
        if ($lapangan->status !== 'aktif') {
            abort(404);
        }
        return view('lapangan.show', compact('lapangan'));
    }

    // ===============
    // PARTNER ONLY
    // ===============

public function index()
{
    $this->authorize('partner');
    
    // ✅ Hanya tampilkan lapangan milik sendiri
    $lapangans = Lapangan::where('user_id', Auth::id())
                         ->latest()
                         ->paginate(10);

    return view('lapangan.partner.index', compact('lapangans'));
}

    public function create()
    {
        $this->authorize('partner');
        return view('lapangan.partner.create');
    }

    public function store(Request $request)
{
    $this->authorize('partner');
    
    $request->validate([
        'nama' => 'required|string|max:255',
        'lokasi' => 'required|string|max:255',
        'kapasitas' => 'required|integer|min:1',
        'harga' => 'required|integer|min:10000',
        'status' => 'required|in:aktif,nonaktif',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $lapangan = new Lapangan();
    $lapangan->user_id = Auth::id(); // ✅ Otomatis set mitra
    $lapangan->nama = $request->nama;
    $lapangan->lokasi = $request->lokasi;
    $lapangan->kapasitas = $request->kapasitas;
    $lapangan->harga = $request->harga;
    $lapangan->status = $request->status;

    if ($request->hasFile('gambar')) {
        $lapangan->gambar = $request->file('gambar')->store('lapangans', 'public');
    }

    $lapangan->save();

    return redirect()->route('partner.lapangan.index')
                    ->with('success', 'Lapangan "' . $lapangan->nama . '" berhasil ditambahkan!');
}

    public function edit(Lapangan $lapangan)
    {
        $this->authorize('partner');
        
        // ✅ Pastikan mitra hanya edit lapangannya sendiri
        if ($lapangan->partner_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengedit lapangan ini.');
        }

        return view('lapangan.partner.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $this->authorize('partner');
        
        // ✅ Validasi lengkap
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'harga' => 'required|integer|min:10000',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ Pastikan akses pemilik
        if ($lapangan->partner_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengupdate lapangan ini.');
        }

        $lapangan->update([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'kapasitas' => $request->kapasitas,
            'harga' => $request->harga,
            'status' => $request->status,
        ]);

        // Handle gambar
        if ($request->hasFile('gambar')) {
            if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
                Storage::disk('public')->delete($lapangan->gambar);
            }
            $lapangan->gambar = $request->file('gambar')->store('lapangan', 'public');
        }

        return redirect()->route('partner.lapangan.index')
                        ->with('success', 'Lapangan "' . $lapangan->nama . '" berhasil diperbarui!');
    }

    public function destroy(Lapangan $lapangan)
    {
        $this->authorize('partner');
        
        // ✅ Pastikan akses pemilik
        if ($lapangan->partner_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menghapus lapangan ini.');
        }

        if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
            Storage::disk('public')->delete($lapangan->gambar);
        }

        $nama = $lapangan->nama;
        $lapangan->delete();

        return redirect()->route('partner.lapangan.index')
                        ->with('success', 'Lapangan "' . $nama . '" berhasil dihapus!');
    }

    // ✅ Helper authorize sesuai aturan Anda
    private function authorize($role)
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Akses ditolak.');
        }
    }
}