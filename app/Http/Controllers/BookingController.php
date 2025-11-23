<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        // ✅ Perbaikan: min:1, max:12 (bukan min=1/max=4)
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi' => 'required|integer|min:1|max:12',
        ]);

        // Parsing waktu
        $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $jamSelesai = $jamMulai->copy()->addHours($request->durasi);

        // Validasi ketersediaan
        $isAvailable = !Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $request->tanggal)
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai->format('H:i'), $jamSelesai->format('H:i')])
                  ->orWhereBetween('jam_selesai', [$jamMulai->format('H:i'), $jamSelesai->format('H:i')])
                  ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                      $q2->where('jam_mulai', '<=', $jamMulai->format('H:i'))
                         ->where('jam_selesai', '>=', $jamSelesai->format('H:i'));
                  });
            })->exists();

        if (!$isAvailable) {
            return back()->withErrors([
                'jam_mulai' => 'Jadwal bentrok. Silakan pilih waktu lain.'
            ])->withInput();
        }

        // Buat booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'lapangan_id' => $lapangan->id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $jamMulai->format('H:i'),
            'jam_selesai' => $jamSelesai->format('H:i'),
            'durasi' => $request->durasi,
            'total_harga' => $lapangan->harga * $request->durasi,
            'status' => 'menunggu_pembayaran', // ✅ Sesuai alur Midtrans
        ]);

        // Redirect ke Midtrans
        return redirect()->route('payment.process', $booking);
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
        if ($booking->user_id !== Auth::id() || !in_array($booking->status, ['menunggu', 'menunggu_pembayaran'])) {
            abort(403);
        }
        $booking->update(['status' => 'dibatalkan']);
        return redirect()->route('booking.index')->with('success', 'Booking dibatalkan.');
    }

    public function createFromCart()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang kosong.');
        }
        $lapangans = Lapangan::where('status', 'aktif')->get();
        return view('booking.from-cart', compact('lapangans', 'cart'));
    }

    public function storeFromCart(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi' => 'required|integer|min:1|max:12',
        ]);

        return $this->storeOrderNow($request, Lapangan::findOrFail($request->lapangan_id));
    }
}