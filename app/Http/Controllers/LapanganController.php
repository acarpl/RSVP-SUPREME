<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::latest()->get();
        return view('lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('lapangan.create');
    }

    // Menyimpan lapangan baru, menerima cropped_gambar (base64) dari form
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer',
            'harga' => 'nullable|numeric',
            'cropped_gambar' => 'nullable|string', // base64
        ]);

        $path = null;

        if (!empty($validated['cropped_gambar'])) {
            $path = $this->saveBase64Image($validated['cropped_gambar']);
        }

        $lapangan = Lapangan::create([
            'nama' => $validated['nama'],
            'lokasi' => $validated['lokasi'] ?? null,
            'kapasitas' => $validated['kapasitas'] ?? null,
            'harga' => $validated['harga'] ?? null,
            'gambar' => $path,
        ]);

        return redirect()->route('lapangan.index')->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function edit(Lapangan $lapangan)
    {
        return view('lapangan.edit', compact('lapangan'));
    }

    // Update lapangan, menangani gambar base64 dan hapus gambar lama bila perlu
    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer',
            'harga' => 'nullable|numeric',
            'cropped_gambar' => 'nullable|string', // base64
        ]);

        // Jika ada gambar baru (base64), simpan dan hapus yang lama
        if (!empty($validated['cropped_gambar'])) {
            $newPath = $this->saveBase64Image($validated['cropped_gambar']);

            // hapus gambar lama jika ada
            if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
                Storage::disk('public')->delete($lapangan->gambar);
            }

            $lapangan->gambar = $newPath;
        }

        // update field lain
        $lapangan->nama = $validated['nama'];
        $lapangan->lokasi = $validated['lokasi'] ?? $lapangan->lokasi;
        $lapangan->kapasitas = $validated['kapasitas'] ?? $lapangan->kapasitas;
        $lapangan->harga = $validated['harga'] ?? $lapangan->harga;

        $lapangan->save();

        return redirect()->route('lapangan.index')->with('success', 'Lapangan berhasil diperbarui!');
    }

    public function destroy(Lapangan $lapangan)
    {
        // Hapus gambar jika ada
        if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
            Storage::disk('public')->delete($lapangan->gambar);
        }

        $lapangan->delete();

        return redirect()->route('lapangan.index')->with('success', 'Lapangan berhasil dihapus!');
    }

    /**
     * Helper: simpan gambar yang dikirim sebagai data URL (base64).
     * Mengembalikan path relatif yang disimpan di disk 'public', contohnya 'lapangan/xyz.png'
     */
    protected function saveBase64Image(string $dataUrl)
    {
        // format: data:image/png;base64,AAA...
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $imageData = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $imageData = base64_decode($imageData);
            $extension = strtolower($type[1]);
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                // fallback
                $extension = 'png';
            }
            $filename = 'lapangan/' . Str::random(20) . '.' . $extension;
            Storage::disk('public')->put($filename, $imageData);
            return $filename;
        }

        return null;
    }
}
