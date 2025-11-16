<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PartnerController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register-partner');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        // buat user dengan role partner
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'partner',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi partner berhasil! Silakan login.');
    }
}
