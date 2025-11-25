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
                          ->select('id', 'created_at', 'total_harga', 'status')
                          ->latest()
                          ->paginate(10);

        return view('history.index', compact('bookings'));
    }
}