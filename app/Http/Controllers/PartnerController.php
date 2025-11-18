<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerController extends Controller
{
    // Tampilkan Form Daftar Mitra
    public function showForm()
    {
        return view('partner.register');
    }

    // Proses daftar mitra
    public function register(Request $request)
    {
        $request->validate([
            'nama_usaha'   => 'required|string|max:255',
            'alamat_usaha' => 'required|string|max:500',
            'telepon'      => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        // Jika sudah menjadi mitra
        if ($user->role === 'partner') {
            return redirect()->back()->with('info', 'Anda sudah terdaftar sebagai Mitra.');
        }

        // Update data user
        $user->role = 'partner';
        $user->nama_usaha = $request->nama_usaha;     // pastikan kolom ini ada pada tabel users atau pindah ke tabel partners
        $user->address = $request->alamat_usaha;      // menyimpan di kolom address
        $user->phone = $request->telepon;             // menyimpan nomor telepon
        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Pendaftaran Mitra Berhasil! Selamat, Anda sekarang adalah Mitra Sportykuy.');
    }

     public function leave()
    {
        $user = Auth::user();

        // ubah role / status partner
        $user->role = 'user';       // jika pakai role
        // $user->is_partner = false;  // jika pakai boolean
        $user->save();

        return redirect()->route('home')->with('success', 'Anda keluar dari mode partner.');
}
}
