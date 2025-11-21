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
        // ✅ PERBAIKAN UTAMA: ganti min=1 → min:1, max=4 → max:4
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi' => 'required|integer|min:1|max:12', // ✅ max:12 (bukan max=4)
        ]);

        $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $durasi = $request->durasi;
        $jamSelesai = $jamMulai->copy()->addHours($durasi);

        // ✅ PERBAIKAN: Validasi bentrok jadwal lebih akurat
        $isAvailable = !Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $request->tanggal)
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->where(function ($q) use ($jamMulai, $jamSelesai) {
                    // Kasus 1: Jadwal baru di dalam jadwal lama
                    $q->where('jam_mulai', '<=', $jamMulai->format('H:i'))
                      ->where('jam_selesai', '>=', $jamSelesai->format('H:i'));
                })->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                    // Kasus 2: Jadwal lama di dalam jadwal baru
                    $q->where('jam_mulai', '>=', $jamMulai->format('H:i'))
                      ->where('jam_selesai', '<=', $jamSelesai->format('H:i'));
                })->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                    // Kasus 3: Overlap awal
                    $q->where('jam_mulai', '<', $jamSelesai->format('H:i'))
                      ->where('jam_selesai', '>', $jamMulai->format('H:i'));
                });
            })->exists();

        if (!$isAvailable) {
            return back()->withErrors([
                'jam_mulai' => 'Jadwal bentrok dengan booking lain. Pilih waktu lain.'
            ])->withInput();
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'lapangan_id' => $lapangan->id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $jamMulai->format('H:i'),
            'jam_selesai' => $jamSelesai->format('H:i'),
            'durasi' => $durasi,
            'total_harga' => $lapangan->harga * $durasi,
            'status' => 'menunggu',
        ]);

        return redirect()->route('booking.checkout', $booking)
                        ->with('success', 'Booking berhasil! Silakan lanjut ke pembayaran.');
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
        return redirect()->route('booking.index')->with('success', 'Booking dibatalkan.');
    }

    public function createFromCart()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->withErrors('Keranjang kosong.');
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