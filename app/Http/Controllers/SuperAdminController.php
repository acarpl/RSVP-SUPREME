<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPartners = User::where('role', 'partner')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        return view('superadmin.dashboard', compact('totalUsers', 'totalPartners', 'totalCustomers'));
    }
}