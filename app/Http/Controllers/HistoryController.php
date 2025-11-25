<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
                          ->with('items')
                          ->latest()
                          ->paginate(10);

        return view('history.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load('items', 'lapangan');
        return view('history.show', compact('booking'));
    }
}