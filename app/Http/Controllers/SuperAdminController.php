<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lapangan;
use App\Models\Product;

class SuperAdminController extends Controller
{
    public function dashboard()
{
    return view('superadmin.dashboard', [
        'totalUsers' => User::count(),
        'totalPartners' => User::where('role', 'partner')->count(),
        'totalLapangan' => Lapangan::count(),
        'totalProduk' => Product::count(),
    ]);
}
}