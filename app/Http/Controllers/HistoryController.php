<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ✅ Ambil semua booking & order
        $bookings = Booking::where('user_id', $user->id)
            ->select('id', 'created_at', 'total_harga', 'status', 'jenis_pesanan', 'alamat_pengiriman')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'type' => 'booking',
                    'created_at' => $booking->created_at,
                    'total_harga' => $booking->total_harga,
                    'status' => $booking->status,
                    'jenis' => $booking->jenis_pesanan ?? 'lapangan',
                    'has_alamat' => !empty($booking->alamat_pengiriman),
                ];
            });

        $orders = Order::where('user_id', $user->id)
            ->select('id', 'created_at', 'total', 'status', 'jenis_pesanan', 'alamat_pengiriman')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'type' => 'order',
                    'created_at' => $order->created_at,
                    'total_harga' => $order->total, // sesuaikan kolom
                    'status' => $order->status,
                    'jenis' => $order->jenis_pesanan ?? 'beli_produk',
                    'has_alamat' => !empty($order->alamat_pengiriman),
                ];
            });

        // ✅ Gabung & urutkan descending
        $history = $bookings->merge($orders)
            ->sortByDesc('created_at')
            ->values();

        // ✅ Paginasi manual (karena Collection)
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $history->forPage($currentPage, $perPage),
            $history->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        return view('history.index', compact('paginated'));
    }
}