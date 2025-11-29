<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    // =============== CUSTOMER/GUEST ===============

    public function customerIndex(Request $request)
    {
        $query = Lapangan::where('status', 'aktif');

        // Pencarian umum (nama/lokasi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kota (dari kolom `lokasi`)
        if ($request->filled('kota')) {
            $kota = $request->kota;
            $query->where('lokasi', 'like', "%{$kota}%");
        }

        // Filter berdasarkan tipe (dari kolom `nama`)
        if ($request->filled('tipe')) {
            $tipe = $request->tipe;

            // Keyword mapping untuk deteksi tipe dari `nama`
            $keywords = [
                'futsal'     => ['futsal', 'lapangan futsal', 'futsal arena'],
                'badminton'  => ['badminton', 'bulutangkis', 'lapangan badminton'],
                'basket'     => ['basket', 'basketball', 'lapangan basket'],
                'tenis'      => ['tenis', 'tennis', 'lapangan tenis'],
            ];

            if (isset($keywords[$tipe])) {
                $query->where(function ($q) use ($keywords, $tipe) {
                    foreach ($keywords[$tipe] as $keyword) {
                        $q->orWhere('nama', 'like', "%{$keyword}%");
                    }
                });
            }
        }

        $lapangans = $query->latest()->paginate(6);

        // Kirim data filter ke view
        return view('lapangan.index', compact('lapangans'))
               ->with('search', $request->search ?? '')
               ->with('kota', $request->kota ?? '')
               ->with('tipe', $request->tipe ?? '');
    }

    public function customerShow(Lapangan $lapangan)
    {
        if ($lapangan->status !== 'aktif') {
            abort(404);
        }
        return view('lapangan.show', compact('lapangan'));
    }

    // =============== PARTNER ONLY ===============

    public function index()
    {
        $this->authorizePartner();

        $lapangans = Lapangan::where('partner_id', Auth::id())
                             ->latest()
                             ->paginate(10);

        return view('lapangan.partner.index', compact('lapangans'));
    }

    public function create()
    {
        $this->authorizePartner();
        return view('lapangan.partner.create');
    }

    public function store(Request $request)
    {
        $this->authorizePartner();

        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'harga' => 'required|integer|min:10000',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $lapangan = new Lapangan();
        $lapangan->partner_id = Auth::id(); 
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
        $this->authorizePartner();
        $this->authorizeOwnership($lapangan);
        return view('lapangan.partner.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $this->authorizePartner();
        $this->authorizeOwnership($lapangan);

        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'harga' => 'required|integer|min:10000',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $lapangan->update([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'kapasitas' => $request->kapasitas,
            'harga' => $request->harga,
            'status' => $request->status,
        ]);

        if ($request->hasFile('gambar')) {
            if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
                Storage::disk('public')->delete($lapangan->gambar);
            }
            $lapangan->gambar = $request->file('gambar')->store('lapangans', 'public');
        }

        return redirect()->route('partner.lapangan.index')
                        ->with('success', 'Lapangan "' . $lapangan->nama . '" berhasil diperbarui!');
    }

    public function destroy(Lapangan $lapangan)
    {
        $this->authorizePartner();
        $this->authorizeOwnership($lapangan);

        if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
            Storage::disk('public')->delete($lapangan->gambar);
        }

        $nama = $lapangan->nama;
        $lapangan->delete();

        return redirect()->route('partner.lapangan.index')
                        ->with('success', 'Lapangan "' . $nama . '" berhasil dihapus!');
    }

    // =============== HELPER ===============

    private function authorizePartner()
    {
        if (!Auth::check() || Auth::user()->role !== 'partner') {
            abort(403, 'Akses ditolak. Hanya partner yang diizinkan.');
        }
    }

    private function authorizeOwnership(Lapangan $lapangan)
    {
        if ($lapangan->partner_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses lapangan ini.');
        }
    }
}