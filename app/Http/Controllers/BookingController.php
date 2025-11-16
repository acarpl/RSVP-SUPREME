<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    // Halaman utama booking (opsional)
    public function index()
    {
        return view('booking.index');
    }

    // Halaman form booking berdasarkan lapangan
    public function create($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        return view('booking.form', compact('lapangan'));
    }

    // Simpan hasil booking
    public function store(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        Reservasi::create([
          'user_id' => Auth::id(),
            'lapangan_id' => $id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('home')->with('success', 'Booking berhasil dibuat!');
    }
}
