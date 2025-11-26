<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Reset email verification jika email diubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    // ======================================================
    //               FUNGSI MITRA / PARTNER
    // ======================================================

    /**
     * Menjadikan user sebagai partner langsung (tanpa form).
     */
    public function becomePartner(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cegah jika user sudah partner atau super admin
        if (in_array($user->role, ['partner', 'super_admin'])) {
            return back()->with('info', 'Anda sudah menjadi mitra.');
        }

        $user->role = 'partner';
        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Selamat! Anda resmi menjadi Mitra Sportykuy.');
    }

    /**
     * Menampilkan form pendaftaran mitra.
     */
   public function partnerForm()
{
    // Pastikan hanya pengguna yang login bisa mengakses (route sudah protected by auth)
    return view('profile.daftar_mitra');
}

/**
 * Proses registrasi mitra lewat form.
 */
public function registerPartner(Request $request)
{
    $request->validate([
        'nama_usaha'   => 'required|string|max:255',
        'alamat_usaha' => 'required|string|max:1000',
        'telepon'      => 'nullable|string|max:30',
    ]);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Jika bukan customer, tidak boleh daftar ulang
    if ($user->role !== 'customer') {
        return back()->with('info', 'Anda sudah terdaftar sebagai mitra.');
    }

    // Update role dan simpan data usaha sederhana ke kolom yang ada
    $user->role = 'partner';
    // simpan phone/address ke kolom existing (pastikan kolom ada di migration)
    if ($request->filled('telepon')) {
        $user->phone = $request->telepon;
    }
    if ($request->filled('alamat_usaha')) {
        $user->address = $request->alamat_usaha;
    }
    // jika ingin menyimpan nama usaha, pastikan kolom 'nama_usaha' ada; jika tidak, simpan di profile lain
    if (Schema::hasColumn('users', 'is_partner')) {
    // Kolom ada → update
    $request->user()->update([
        'is_partner' => true,
        'partner_status' => 'pending'
    ]);
}

    $user->save();

    return redirect()
        ->route('profile.edit')
        ->with('success', 'Selamat! Pendaftaran Mitra Berhasil, akun Anda kini adalah Partner.');
}

public function leavePartner()
{
    $user = auth()->user();
    $user->role = 'customer';
    $user->save();

    return redirect()->route('home')->with('success', 'Berhasil keluar dari partner.');
}

}