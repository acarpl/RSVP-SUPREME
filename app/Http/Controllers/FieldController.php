<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        // Hanya tampilkan lapangan yang aktif (misalnya)
        $lapangans = Lapangan::where('status', 'aktif')->latest()->get();
        return view('field.index', compact('lapangans'));
    }

    public function show(Lapangan $lapangan)
    {
        // Pastikan hanya lapangan aktif yang bisa dilihat
        if ($lapangan->status !== 'aktif') {
            abort(404);
        }
        return view('field.show', compact('lapangan'));
    }
}