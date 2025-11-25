<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('user_id', Auth::id())
                        ->with(['items.product', 'lapangan'])
                        ->latest();

        // Filter berdasarkan jenis pesanan
        if ($request->filled('jenis')) {
            if ($request->jenis === 'beli_produk') {
                $query->whereNotNull('alamat_pengiriman');
            } else {
                $query->whereNull('alamat_pengiriman');
            }
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(8);

        return view('history.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('history.show', compact('booking'));
    }
}