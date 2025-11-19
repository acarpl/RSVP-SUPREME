<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    // ===============
    // CUSTOMER/GUEST
    // ===============

    public function customerIndex()
    {
        $lapangans = Lapangan::where('status', 'aktif')->latest()->get();
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
        $lapangans = Lapangan::latest()->get();
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
        'harga' => 'required|integer|min:0',
        'status' => 'required|in:aktif,nonaktif',
    ]);

    // ✅ BUAT OBJEK MANUAL (tanpa create mass assignment)
    $lapangan = new Lapangan();
    $lapangan->nama = $request->nama;
    $lapangan->lokasi = $request->lokasi;
    $lapangan->kapasitas = $request->kapasitas;
    $lapangan->harga = $request->harga;
    $lapangan->status = $request->status;

    if ($request->hasFile('gambar')) {
        $lapangan->gambar = $request->file('gambar')->store('lapangan', 'public');
    }

    $lapangan->save();

    return redirect()->route('lapangan.index')
                    ->with('success', 'Lapangan berhasil ditambahkan!');
}

    public function edit(Lapangan $lapangan)
    {
        $this->authorize('partner');
        return view('lapangan.partner.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $this->authorize('partner');
        
        // ✅ Perbaikan: validasi dengan TITIK DUA (:)
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255', // ✅ required
            'kapasitas' => 'nullable|integer|min:0', // ✅ min:0
            'harga' => 'required|integer|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ✅ Perbaikan: gunakan only()
        $data = $request->only(['nama', 'lokasi', 'kapasitas', 'harga', 'status']);

        // Handle gambar
        if ($request->hasFile('gambar')) {
            if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
                Storage::disk('public')->delete($lapangan->gambar);
            }
            $path = $request->file('gambar')->store('lapangan', 'public');
            $data['gambar'] = $path;
        }

        $lapangan->update($data);

        return redirect()->route('partner.lapangan.index')
                        ->with('success', 'Lapangan "' . $lapangan->nama . '" berhasil diperbarui!');
    }

    public function destroy(Lapangan $lapangan)
    {
        $this->authorize('partner');
        
        if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
            Storage::disk('public')->delete($lapangan->gambar);
        }
        $nama = $lapangan->nama;
        $lapangan->delete();

        return redirect()->route('partner.lapangan.index')
                        ->with('success', 'Lapangan "' . $nama . '" berhasil dihapus!');
    }

    private function authorize($role)
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403);
        }
    }
}