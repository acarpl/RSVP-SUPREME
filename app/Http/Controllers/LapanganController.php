<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LapanganController extends Controller
{
    // ✅ CUSTOMER: lihat daftar lapangan aktif
    public function customerIndex()
    {
        $lapangans = Lapangan::where('status', 'aktif')->latest()->get();
        return view('lapangan.index', compact('lapangans'));
    }

    // ✅ CUSTOMER: lihat detail lapangan
    public function customerShow(Lapangan $lapangan)
    {
        if ($lapangan->status !== 'aktif') {
            abort(404);
        }
        return view('lapangan.show', compact('lapangan'));
    }

    // ✅ PARTNER: lihat semua lapangan (termasuk non-aktif)
    public function index()
    {
        $lapangans = Lapangan::latest()->get();
        return view('lapangan.partner.index', compact('lapangans'));
    }

    // ✅ PARTNER: form tambah
    public function create()
    {
        return view('lapangan.partner.create');
    }

    // ✅ PARTNER: simpan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'cropped_gambar' => 'nullable|string',
        ]);

        $path = null;
        if (!empty($validated['cropped_gambar'])) {
            $path = $this->saveBase64Image($validated['cropped_gambar']);
        }

        Lapangan::create([
            'nama' => $validated['nama'],
            'lokasi' => $validated['lokasi'] ?? null,
            'kapasitas' => $validated['kapasitas'] ?? null,
            'harga' => $validated['harga'],
            'status' => $validated['status'],
            'gambar' => $path,
        ]);

        return redirect()->route('partner.lapangan.index')
                         ->with('success', 'Lapangan berhasil ditambahkan!');
    }

    // ✅ PARTNER: form edit
    public function edit(Lapangan $lapangan)
    {
        return view('lapangan.partner.edit', compact('lapangan'));
    }

    // ✅ PARTNER: update
    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'cropped_gambar' => 'nullable|string',
        ]);

        // Handle gambar baru
        if (!empty($validated['cropped_gambar'])) {
            $newPath = $this->saveBase64Image($validated['cropped_gambar']);
            // Hapus gambar lama
            if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
                Storage::disk('public')->delete($lapangan->gambar);
            }
            $lapangan->gambar = $newPath;
        }

        $lapangan->update([
            'nama' => $validated['nama'],
            'lokasi' => $validated['lokasi'] ?? $lapangan->lokasi,
            'kapasitas' => $validated['kapasitas'] ?? $lapangan->kapasitas,
            'harga' => $validated['harga'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('partner.lapangan.index')
                         ->with('success', 'Lapangan berhasil diperbarui!');
    }

    // ✅ PARTNER: hapus
    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
            Storage::disk('public')->delete($lapangan->gambar);
        }
        $lapangan->delete();

        return redirect()->route('partner.lapangan.index')
                         ->with('success', 'Lapangan berhasil dihapus!');
    }

    // ✅ Helper: simpan base64 ke storage
    protected function saveBase64Image(string $dataUrl)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $imageData = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $imageData = base64_decode($imageData);
            $extension = strtolower($type[1]);
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $extension = 'png';
            }
            $filename = 'lapangan/' . Str::random(20) . '.' . $extension;
            Storage::disk('public')->put($filename, $imageData);
            return $filename;
        }
        return null;
    }
}