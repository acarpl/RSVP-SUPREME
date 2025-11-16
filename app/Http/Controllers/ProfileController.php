<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
        return view('profile.daftar-mitra');
    }

    /**
     * Proses registrasi mitra lewat form.
     */
    public function registerPartner(Request $request)
    {
        $request->validate([
            'nama_usaha'   => 'required|string|max:255',
            'alamat_usaha' => 'required|string',
        ]);

        $user = Auth::user();

        // Jika bukan customer, tidak boleh daftar ulang
        if (!in_array($user->role, ['customer'])) {
            return back()->with('info', 'Anda sudah terdaftar sebagai mitra.');
        }

        // Update role user
        $user->update([
            'role' => 'partner',
        ]);

        // Jika ingin menyimpan data usaha, bisa simpan ke tabel lain di sini

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Selamat! Pendaftaran Mitra Berhasil, akun Anda kini adalah Partner.');
    }
}
