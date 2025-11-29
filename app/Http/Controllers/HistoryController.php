<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil booking dan ubah ke stdClass (bukan array murni)
        $bookings = Booking::where('user_id', $user->id)
            ->select('id', 'created_at', 'total_harga', 'status', 'jenis_pesanan', 'alamat_pengiriman')
            ->get()
            ->map(function ($booking) {
                return (object) [
                    'id' => $booking->id,
                    'type' => 'booking',
                    'created_at' => $booking->created_at,
                    'total_harga' => $booking->total_harga,
                    'status' => $booking->status,
                    'jenis' => $booking->jenis_pesanan ?? 'lapangan',
                    'has_alamat' => !empty($booking->alamat_pengiriman),
                ];
            });

        // Ambil order dan ubah ke stdClass
        $orders = Order::where('user_id', $user->id)
            ->select('id', 'created_at', 'total', 'status', 'jenis_pesanan', 'alamat_pengiriman')
            ->get()
            ->map(function ($order) {
                return (object) [
                    'id' => $order->id,
                    'type' => 'order',
                    'created_at' => $order->created_at,
                    'total_harga' => $order->total,
                    'status' => $order->status,
                    'jenis' => $order->jenis_pesanan ?? 'beli_produk',
                    'has_alamat' => !empty($order->alamat_pengiriman),
                ];
            });

        // Gabung, urutkan descending berdasarkan created_at
        $history = $bookings->concat($orders)
            ->sortByDesc('created_at')
            ->values(); // reset keys

        // Paginasi manual aman
        $perPage = 10;
        $currentPage = (int) request()->get('page', 1);
        $currentPage = max(1, $currentPage);

        $paginatedItems = $history->slice(($currentPage - 1) * $perPage, $perPage);
        $total = $history->count();

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(), // preserve existing query params
            ]
        );

        return view('history.index', compact('paginated'));
    }
}