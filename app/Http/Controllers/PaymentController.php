<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function create($lapanganId)
    {
        $lapangan = Lapangan::findOrFail($lapanganId);
        if ($lapangan->status !== 'aktif') {
            abort(404, 'Lapangan tidak tersedia.');
        }
        return view('booking.order-now', compact('lapangan'));
    }

    public function store(Request $request, $lapanganId)
    {
        $request->validate([
    'tanggal' => 'required|date|after_or_equal:today',
    'jam_mulai' => 'required|date_format:H:i',
    'durasi' => 'required|integer|min:1|max:12', // ✅
]);

        $lapangan = Lapangan::findOrFail($lapanganId);
        $jamMulai = $request->jam_mulai;
        $jamSelesai = date('H:i', strtotime("$jamMulai +{$request->durasi} hours"));

        // Cek ketersediaan
        $bentrok = Booking::where('lapangan_id', $lapangan->id)
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

        if ($bentrok) {
            return back()->withErrors(['jam_mulai' => 'Jadwal bentrok.']);
        }

        // Buat booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'lapangan_id' => $lapangan->id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'durasi' => $request->durasi,
            'total_harga' => $lapangan->harga * $request->durasi,
            'status' => 'menunggu_pembayaran',
        ]);

        // Redirect ke halaman redirect (akan generate SNAP URL)
        return redirect()->route('payment.redirect', $booking);
    }

    // ✅ GENERATE SNAP REDIRECT URL (bukan token)
    public function redirect(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() || $booking->status !== 'menunggu_pembayaran') {
            abort(403, 'Booking tidak valid.');
        }

        // Buat order_id unik
        $orderId = 'SPORTY-' . now()->format('YmdHis') . '-' . $booking->id;

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_harga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?: '081234567890',
            ],
            'item_details' => [
                [
                    'id' => 'booking-' . $booking->id,
                    'price' => (int) $booking->total_harga,
                    'quantity' => 1,
                    'name' => 'Booking ' . $booking->lapangan->nama,
                ]
            ],
            // ✅ Redirect URLs (wajib untuk redirect mode)
            'credit_card' => [
                'secure' => true,
            ],
        ];

        try {
            // ✅ Dapatkan SNAP redirect URL
            $snapUrl = \Midtrans\Snap::createTransaction($payload)->redirect_url;

            // Simpan order_id
            $booking->update([
                'order_id' => $orderId,
            ]);

            // ✅ Redirect ke Midtrans (bukan ke halaman internal)
            return redirect()->away($snapUrl);

        } catch (\Exception $e) {
            \Log::error('Midtrans Redirect Error', [
                'message' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            return back()->withErrors('Gagal membuka halaman pembayaran: ' . $e->getMessage());
        }
    }

    // ✅ CEK STATUS SETELAH REDIRECT KE /finish
    public function finish(Booking $booking)
    {
        // Validasi kepemilikan
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            // ✅ Ambil status terkini dari Midtrans (server-to-server)
            $status = Transaction::status($booking->order_id);

            \Log::info('Midtrans status check', [
                'order_id' => $booking->order_id,
                'transaction_status' => $status->transaction_status ?? 'unknown',
                'fraud_status' => $status->fraud_status ?? 'accept',
            ]);

            // Update status berdasarkan respons
            $newStatus = 'menunggu_pembayaran';
            $transactionStatus = $status->transaction_status ?? 'unknown';
            $fraudStatus = $status->fraud_status ?? 'accept';

            if (in_array($transactionStatus, ['capture', 'settlement']) && $fraudStatus === 'accept') {
                $newStatus = 'dibayar';
            } elseif (in_array($transactionStatus, ['cancel', 'expire'])) {
                $newStatus = 'dibatalkan';
            }

            $booking->update(['status' => $newStatus]);

            // Redirect ke halaman sukses/booking detail
            if ($newStatus === 'dibayar') {
                return redirect()->route('booking.show', $booking)
                    ->with('success', '✅ Pembayaran berhasil! Booking Anda dikonfirmasi.');
            } else {
                return redirect()->route('booking.show', $booking)
                    ->with('warning', 'ℹ️ Status: ' . $newStatus);
            }

        } catch (\Exception $e) {
            \Log::error('Finish check error', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('booking.show', $booking)
                ->with('error', 'Gagal memeriksa status pembayaran. Silakan cek email/notifikasi.');
        }
    }

    public function error()
    {
        return redirect()->route('booking.index')
            ->withErrors('Pembayaran dibatalkan atau gagal.');
    }
}