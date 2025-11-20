<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerDashboardController extends Controller
{
public function index()
{
    $user = Auth::user();
    
    // ✅ Hanya ambil lapangan milik mitra yang sedang login
    $lapanganIds = Lapangan::where('user_id', $user->id)->pluck('id');

    // Statistik hanya untuk lapangan miliknya
    $totalLapangan = $lapanganIds->count();
    $totalBooking = Booking::whereIn('lapangan_id', $lapanganIds)->count();
    $bookingAktif = Booking::whereIn('lapangan_id', $lapanganIds)
                          ->where('status', 'pending')->count();
    $pendapatan = Booking::whereIn('lapangan_id', $lapanganIds)
                        ->where('status', 'confirmed')->sum('total_price');

    // Ambil 5 booking terbaru untuk lapangan miliknya
    $latestBookings = Booking::whereIn('lapangan_id', $lapanganIds)
                            ->with('lapangan')
                            ->latest()
                            ->take(5)
                            ->get();

    // Data chart 7 hari
    $bookingsChart = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $count = Booking::whereIn('lapangan_id', $lapanganIds)
                       ->whereDate('created_at', $date)->count();
        $bookingsChart[] = [
            'date' => now()->subDays($i)->format('d M'),
            'count' => $count
        ];
    }

    return view('partner.dashboard', compact(
        'totalLapangan',
        'totalBooking',
        'bookingAktif',
        'pendapatan',
        'latestBookings',
        'bookingsChart'
    ));
}
}