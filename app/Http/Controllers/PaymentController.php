<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\FacadesLog;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // ✅ Pastikan curlOptions selalu array (fix "Undefined array key 10023" di PHP 8.4+)
        Config::$curlOptions = Config::$curlOptions ?? [];

        // ✅ SSL bypass hanya untuk local (aman untuk dev)
        if (app()->environment('local')) {
            Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
            Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
        }

        // ✅ Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // ✅ Validasi kritis
        if (empty(Config::$serverKey)) {
            throw new \Exception('MIDTRANS_SERVER_KEY belum diisi di .env');
        }
    }

    // =============== USER FLOW ===============

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
            'durasi' => 'required|integer|min:1|max:12',
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
            return back()->withErrors(['jam_mulai' => 'Jadwal bentrok. Silakan pilih waktu lain.']);
        }

        $totalHarga = (int) ($lapangan->harga * $request->durasi);
        if ($totalHarga <= 0) {
            return back()->withErrors(['harga' => 'Harga tidak valid. Hubungi admin.']);
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'lapangan_id' => $lapangan->id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'durasi' => $request->durasi,
            'total_harga' => $totalHarga,
            'status' => 'menunggu_pembayaran',
        ]);

        return redirect()->route('payment.redirect', $booking)
                        ->with('info', 'Silakan selesaikan pembayaran dalam 15 menit.');
    }

    // =============== MIDTRANS SNAP REDIRECT ===============

    public function redirect(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() || $booking->status !== 'menunggu_pembayaran') {
            abort(403, 'Booking tidak valid.');
        }

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
            'item_details' => [[
                'id' => 'booking-' . $booking->id,
                'price' => (int) $booking->total_harga,
                'quantity' => 1,
                'name' => 'Booking ' . $booking->lapangan->nama,
            ]],
            'credit_card' => ['secure' => true],
            'finish_redirect_url' => route('payment.finish', $booking),
            'unfinish_redirect_url' => route('payment.error'),
            'error_redirect_url' => route('payment.error'),
        ];

        try {
            $snapResponse = \Midtrans\Snap::createTransaction($payload);
            $booking->update(['order_id' => $orderId]);
            return redirect()->away($snapResponse->redirect_url);

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error', [
                'booking_id' => $booking->id,
                'order_id' => $orderId ?? 'null',
                'message' => $e->getMessage(),
            ]);

            if (app()->environment('local')) {
                abort(500, "Midtrans Error: " . $e->getMessage());
            }

            return back()->withErrors(['Pembayaran gagal dibuka. Silakan coba lagi.']);
        }
    }

    // =============== WEBHOOK OTOMATIS (Midtrans → Server) ===============

    public function notification(Request $request)
{
    Log::info('Webhook received: Midtrans notification triggered');

    try {
        // ✅ Ambil notifikasi dari Midtrans (otomatis parse JSON request body)
        $notif = new Notification();

        $order_id_midtrans = $notif->order_id;
        $transaction_status = $notif->transaction_status;
        $fraud_status = $notif->fraud_status;
        $gross_amount = $notif->gross_amount;

        Log::info('Webhook data', [
            'order_id_midtrans' => $order_id_midtrans,
            'transaction_status' => $transaction_status,
            'fraud_status' => $fraud_status,
            'gross_amount' => $gross_amount,
        ]);

        // 🔍 Cari booking (lapangan)
        $booking = Booking::where('order_id', $order_id_midtrans)->first();
        $order = null;

        // 🔍 Cari order (produk) — jika tidak ketemu booking
        if (!$booking) {
            $order = Order::where('order_id_midtrans', $order_id_midtrans)->first();
        }

        // ❌ Tidak ditemukan di booking maupun order
        if (!$booking && !$order) {
            Log::warning('Booking/Order not found for order_id', [
                'midtrans_order_id' => $order_id_midtrans,
            ]);
            return response('Booking/Order not found', 404);
        }

        // ✅ Mapping status Midtrans → status lokal
        $newStatus = 'menunggu_pembayaran';
        $paymentStatus = 'pending';

        if (in_array($transaction_status, ['capture', 'settlement']) && $fraud_status === 'accept') {
            $newStatus = 'dibayar';
            $paymentStatus = 'settlement';
        } elseif ($transaction_status === 'pending') {
            $newStatus = 'menunggu_pembayaran';
            $paymentStatus = 'pending';
        } elseif (in_array($transaction_status, ['deny', 'cancel', 'expire'])) {
            $newStatus = 'dibatalkan';
            $paymentStatus = 'cancel';
        } elseif ($transaction_status === 'settlement' && $fraud_status === 'challenge') {
            $newStatus = 'menunggu_verifikasi';
            $paymentStatus = 'challenge';
        }

        // ✅ Update status sesuai tipe entitas
        if ($booking) {
            $oldStatus = $booking->status;
            $booking->update([
                'status' => $newStatus,
                // opsional: simpan payment_status di booking jika kolom tersedia
            ]);

            Log::info('Booking status updated', [
                'booking_id' => $booking->id,
                'order_id' => $booking->order_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            // 🔔 Opsional: kirim notifikasi/push/email ke user
            // event(new BookingStatusUpdated($booking));
        }

        if ($order) {
            $oldStatus = $order->status;
            $order->update([
                'status' => $newStatus,
                'payment_status' => $paymentStatus,
            ]);

            Log::info('Order status updated', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'midtrans_order_id' => $order_id_midtrans,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            // 🔔 Opsional: kurangi stok hanya saat dibayar (jika stok dikurangi saat checkout, abaikan)
            // if ($newStatus === 'dibayar') {
            //     foreach ($order->items as $item) {
            //         $item->product()->decrement('stock', $item->quantity);
            //     }
            // }
        }

        // ✅ Sukses — Midtrans butuh response 200 + "OK"
        return response('OK', 200);

    } catch (\Exception $e) {
        Log::error('Webhook processing error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Tetap kirim 200 agar Midtrans tidak retry terus (best practice)
        // Tapi log error untuk debugging manual
        return response('OK', 200);
    }
}

    // =============== MANUAL FINISH (User klik "Selesai") ===============

    public function finish(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $status = Transaction::status($booking->order_id);
            $transaction_status = $status->transaction_status ?? 'unknown';
            $fraud_status = $status->fraud_status ?? 'accept';

            $newStatus = 'menunggu_pembayaran';
            if (in_array($transaction_status, ['capture', 'settlement']) && $fraud_status === 'accept') {
                $newStatus = 'dibayar';
            } elseif (in_array($transaction_status, ['cancel', 'expire'])) {
                $newStatus = 'dibatalkan';
            }

            $booking->update(['status' => $newStatus]);

            if ($newStatus === 'dibayar') {
                return redirect()->route('booking.show', $booking)
                    ->with('success', '✅ Pembayaran berhasil! Booking Anda dikonfirmasi.');
            }

            return redirect()->route('booking.show', $booking)
                ->with('warning', 'ℹ️ Status: ' . $newStatus);

        } catch (\Exception $e) {
            Log::error('Finish check error', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['Gagal memverifikasi pembayaran.']);
        }
    }

    public function error()
    {
        return redirect()->route('booking.index')
            ->with('error', '❌ Pembayaran dibatalkan atau gagal.');
    }
}