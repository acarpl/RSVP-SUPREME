<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
                          ->with('lapangan')
                          ->latest()
                          ->get();
        return view('booking.index', compact('bookings'));
    }

    public function orderNow(Lapangan $lapangan)
    {
        if ($lapangan->status !== 'aktif') {
            abort(404);
        }
        return view('booking.order-now', compact('lapangan'));
    }

    public function storeOrderNow(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'durasi' => 'required|integer|min=1|max=4',
        ]);

        $jamMulai = $request->jam_mulai;
        $durasi = $request->durasi;
        $jamSelesai = date('H:i', strtotime("$jamMulai +{$durasi} hours"));

        $isAvailable = !Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $request->tanggal)
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                  ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                  ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                      $q2->where('jam_mulai', '<=', $jamMulai)
                         ->where('jam_selesai', '>=', $jamSelesai);
                  });
            })->exists();

        if (!$isAvailable) {
            return back()->withErrors(['jam_mulai' => 'Jadwal sudah terbooking'])->withInput();
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'lapangan_id' => $lapangan->id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'durasi' => $durasi,
            'total_harga' => $lapangan->harga * $durasi,
            'status' => 'menunggu',
        ]);

        return redirect()->route('booking.checkout', $booking);
    }

    public function checkout(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);
        return view('booking.checkout', compact('booking'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);
        return view('booking.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() || $booking->status !== 'menunggu') abort(403);
        $booking->update(['status' => 'dibatalkan']);
        return redirect()->route('booking.index');
    }

    public function createFromCart()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('lapangan.index')->withErrors('Keranjang kosong');
        }
        $lapangans = Lapangan::where('status', 'aktif')->get();
        $cartItems = collect($cart)->map(function ($item, $id) {
            return [
                'id' => $id,
                'name' => $item['name'] ?? 'Item',
                'price' => (float) ($item['price'] ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 1),
            ];
        })->values()->all();
        return view('booking.from-cart', compact('lapangans', 'cartItems'));
    }

    public function storeFromCart(Request $request)
    {
        return $this->storeOrderNow($request, Lapangan::findOrFail($request->lapangan_id));
    }
}