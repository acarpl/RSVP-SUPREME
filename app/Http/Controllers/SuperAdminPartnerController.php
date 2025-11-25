<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminPartnerController extends Controller
{
    public function index()
    {
        $partners = User::where('role', 'partner')->with('lapangan')->latest()->paginate(10);
        return view('superadmin.partners.index', compact('partners'));
    }

    // Opsional: detail partner + lapangan & produk mereka
    public function show(User $partner)
    {
        $lapangan = $partner->lapangan; // relasi via user_id/mitra_id
        $produk = $partner->produk;     // jika ada relasi produk
        return view('superadmin.partners.show', compact('partner', 'lapangan', 'produk'));
    }

    // Aktif/nonaktif, reset role → user, dll
    public function suspend(User $partner)
    {
        $partner->update(['role' => 'customer']);
        return back()->with('success', 'Partner ' . $partner->name . ' telah dinonaktifkan.');
    }
}