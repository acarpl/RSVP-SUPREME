<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'customer')->latest()->paginate(10);
        return view('superadmin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        if ($user->role === 'super_admin') abort(403);
        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'super_admin') abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:customer,partner',
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->route('superadmin.users.index')
                         ->with('success', '✅ User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'super_admin') abort(403);
        $user->delete();
        return redirect()->back()->with('success', '🗑️ User berhasil dihapus.');
    }
}