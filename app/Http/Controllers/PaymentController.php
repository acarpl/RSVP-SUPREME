<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use App\Models\Lapangan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // ✅ Fix PHP 8.4 curlOptions issue
        Config::$curlOptions = Config::$curlOptions ?? [];

        // ✅ SSL bypass hanya untuk local (aman untuk dev)
        if (app()->environment('local')) {
            Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
            Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
        }

        // ✅ Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // ✅ Validasi kritis
        if (empty(Config::$serverKey)) {
            throw new \Exception('MIDTRANS_SERVER_KEY belum diisi di .env');
        }
    }

    // =============== BOOKING LAPANGAN ===============

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
        $jamSelesai = date('H:i:s', strtotime("$jamMulai +{$request->durasi} hours"));

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

        // ✅ Simpan dengan format waktu H:i:s & jenis_pesanan
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'jenis_pesanan' => 'lapangan',
            'lapangan_id' => $lapangan->id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $jamMulai . ':00',
            'jam_selesai' => $jamSelesai,
            'durasi' => $request->durasi,
            'total_harga' => $totalHarga,
            'status' => 'menunggu_pembayaran',
        ]);

        return redirect()->route('payment.redirect', $booking)
            ->with('info', 'Silakan selesaikan pembayaran dalam 15 menit.');
    }

    // =============== MIDTRANS SNAP: BOOKING LAPANGAN ===============

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
                'name' => 'Booking ' . ($booking->lapangan->nama ?? 'Lapangan'),
            ]],
            'credit_card' => ['secure' => true],
            'finish_redirect_url' => route('payment.finish', $booking),
            'unfinish_redirect_url' => route('payment.error'),
            'error_redirect_url' => route('payment.error'),
        ];

        try {
            $snapResponse = Snap::createTransaction($payload);
            $booking->update(['order_id' => $orderId]);
            return redirect()->away($snapResponse->redirect_url);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error (Lapangan)', [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);
            return back()->withErrors(['Pembayaran gagal dibuka. Silakan coba lagi.']);
        }
    }

    // =============== MIDTRANS SNAP: ORDER (PRODUK / SEWA ALAT) ===============

    public function processOrder(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'menunggu_pembayaran') {
            abort(403, 'Order tidak valid.');
        }

        $orderId = 'ORDER-' . now()->format('YmdHis') . '-' . $order->id;

        $items = $order->items->map(function ($item) {
            return [
                'id' => 'prod-' . $item->product_id,
                'price' => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name' => $item->name,
            ];
        })->values()->all();

        if (empty($items)) {
            return back()->withErrors(['Pesanan tidak memiliki item.']);
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $order->total_harga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?: '081234567890',
                'billing_address' => $order->jenis_pesanan === 'beli_produk'
                    ? [
                        'first_name' => Auth::user()->name,
                        'address' => $order->alamat ?? '-',
                        'city' => 'Kota Bekasi',
                        'postal_code' => '17143',
                        'country_code' => 'ID',
                    ] : null,
            ],
            'item_details' => $items,
            'credit_card' => ['secure' => true],
            'finish_redirect_url' => route('order.finish', $order),
            'unfinish_redirect_url' => route('order.error'),
            'error_redirect_url' => route('order.error'),
        ];

        try {
            $snapResponse = Snap::createTransaction($payload);
            $order->update(['order_id_midtrans' => $orderId]);
            return redirect()->away($snapResponse->redirect_url);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error (Order)', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);
            return back()->withErrors(['Gagal membuka pembayaran. Silakan coba lagi.']);
        }
    }

    // =============== WEBHOOK OTOMATIS (UNIVERSAL) ===============

    public function notification(Request $request)
    {
        Log::info('Webhook received: Midtrans notification triggered');

        try {
            $notif = new Notification();

            $order_id_midtrans = $notif->order_id;
            $transaction_status = $notif->transaction_status;
            $fraud_status = $notif->fraud_status;

            Log::info('Webhook data', [
                'order_id_midtrans' => $order_id_midtrans,
                'transaction_status' => $transaction_status,
                'fraud_status' => $fraud_status,
            ]);

            // 🔍 Cari booking atau order
            $booking = Booking::where('order_id', $order_id_midtrans)->first();
            $order = $booking ? null : Order::where('order_id_midtrans', $order_id_midtrans)->first();

            if (!$booking && !$order) {
                Log::warning('Booking/Order not found', ['order_id' => $order_id_midtrans]);
                return response('OK', 200); // Midtrans butuh 200
            }

            // ✅ Mapping status
            $newStatus = match(true) {
                in_array($transaction_status, ['capture', 'settlement']) && $fraud_status === 'accept' => 'dibayar',
                $transaction_status === 'pending' => 'menunggu_pembayaran',
                in_array($transaction_status, ['deny', 'cancel', 'expire']) => 'dibatalkan',
                $transaction_status === 'settlement' && $fraud_status === 'challenge' => 'menunggu_verifikasi',
                default => 'menunggu_pembayaran',
            };

            // ✅ Update status & kurangi stok
            if ($booking) {
                $booking->update(['status' => $newStatus]);
                Log::info('Booking status updated', [
                    'id' => $booking->id,
                    'jenis' => $booking->jenis_pesanan ?? 'lapangan',
                    'status' => $newStatus,
                ]);
            }

            if ($order) {
                $order->update(['status' => $newStatus]);

                // 🟢 Kurangi stok saat dibayar
                if ($newStatus === 'dibayar') {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->decrement('stock', $item->quantity);
                            Log::info('Stock decreased', [
                                'product' => $item->product->name,
                                'qty' => $item->quantity,
                            ]);
                        }
                    }
                }

                Log::info('Order status updated', [
                    'id' => $order->id,
                    'jenis' => $order->jenis_pesanan,
                    'status' => $newStatus,
                ]);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Webhook error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response('OK', 200); // tetap 200
        }
    }

    // =============== MANUAL FINISH ===============

    public function finish(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        try {
            $status = Transaction::status($booking->order_id);
                $newStatus = match(true) {
                    in_array($status['transaction_status'] ?? '', ['capture', 'settlement']) && $status['fraud_status'] ?? '' === 'accept' => 'dibayar',
                    in_array($status['transaction_status'] ?? '', ['cancel', 'expire']) => 'dibatalkan',
                    default => 'menunggu_pembayaran',
                };

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

    public function finishOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        try {
            $status = (object) Transaction::status($order->order_id_midtrans);
            $newStatus = $status->transaction_status === 'settlement' ? 'dibayar' : 'menunggu_pembayaran';
            $order->update(['status' => $newStatus]);

            if ($newStatus === 'dibayar') {
                foreach ($order->items as $item) {
                    $item->product?->decrement('stock', $item->quantity);
                }
                return redirect()->route('order.success', $order)
                    ->with('success', '✅ Pesanan berhasil!');
            }

            return back()->with('warning', 'Menunggu pembayaran.');

        } catch (\Exception $e) {
            return back()->withErrors(['Gagal verifikasi.']);
        }
    }

    // =============== ERROR HANDLER ===============

    public function error()
    {
        return redirect()->route('booking.index')
            ->with('error', '❌ Pembayaran dibatalkan atau gagal.');
    }

    public function orderError()
    {
        return redirect()->route('cart.index')
            ->with('error', '❌ Pembayaran dibatalkan.');
    }

    // =============== SUCCESS VIEW ===============

    public function orderSuccess(Order $order)
    {
        return view('order.success', compact('order'));
    }
}