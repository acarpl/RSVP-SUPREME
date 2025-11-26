<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminPartnerController extends Controller
{
    public function index()
    {
        $partners = User::where('role', 'partner')
                        ->withCount('lapangan') // pastikan relasi 'lapangan' ada di model User
                        ->latest()
                        ->paginate(10);
        return view('superadmin.partners.index', compact('partners'));
    }

    public function show(User $partner)
    {
        if ($partner->role !== 'partner') abort(404);
        $lapangan = $partner->lapangan; // relasi: $this->hasMany(Lapangan::class, 'mitra_id');
        return view('superadmin.partners.show', compact('partner', 'lapangan'));
    }

    public function destroy(User $partner)
    {
        if ($partner->role !== 'partner') abort(403);
        $partner->update(['role' => 'customer']);
        return redirect()->back()->with('success', '⏸️ Partner ' . $partner->name . ' telah dinonaktifkan.');
    }
}