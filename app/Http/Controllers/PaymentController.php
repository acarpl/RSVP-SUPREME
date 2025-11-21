<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Langkah 1: Buat transaksi & redirect ke Midtrans
     */
    public function create($lapanganId)
    {
        // Validasi lapangan
        $lapangan = Lapangan::findOrFail($lapanganId);
        if ($lapangan->status !== 'aktif') {
            abort(404, 'Lapangan tidak tersedia.');
        }

        // Redirect ke halaman booking form
        return view('booking.order-now', compact('lapangan'));
    }

    /**
     * Langkah 2: Proses pembayaran setelah form submit
     */
    public function store(Request $request, $lapanganId)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi' => 'required|integer|min:1|max:12',
        ]);

        $lapangan = Lapangan::findOrFail($lapanganId);
        $jamMulai = $request->jam_mulai;
        $jamSelesai = date('H:i', strtotime("$jamMulai +{$request->durasi} hours"));

        // Cek ketersediaan jadwal
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
            return back()->withErrors([
                'jam_mulai' => 'Jadwal bentrok. Silakan pilih waktu lain.'
            ])->withInput();
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

        // Redirect ke pembayaran Midtrans
        return redirect()->route('payment.process', $booking);
    }

    /**
     * Langkah 3: Initiate Midtrans Snap
     */
    public function process(Booking $booking)
    {
        // Validasi booking milik user
        if ($booking->user_id !== Auth::id() || $booking->status !== 'menunggu_pembayaran') {
            abort(403, 'Booking tidak valid.');
        }

        // Data transaksi
        $transactionDetails = [
            'order_id' => 'SPORTYKUY-' . $booking->id . '-' . time(),
            'gross_amount' => $booking->total_harga,
        ];

        $itemDetails = [
            [
                'id' => 'booking-' . $booking->id,
                'price' => $booking->total_harga,
                'quantity' => 1,
                'name' => 'Booking ' . $booking->lapangan->nama . ' (' . $booking->durasi . ' jam)',
            ]
        ];

        $customerDetails = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone ?? '081234567890',
        ];

        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'enabled_payments' => ['gopay', 'bank_transfer', 'credit_card', 'bca_va', 'bni_va', 'bri_va'],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan data transaksi
            $booking->update([
                'snap_token' => $snapToken,
                'order_id' => $transactionDetails['order_id'],
            ]);

            return view('payment.create', compact('booking', 'snapToken'));

        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return back()->withErrors('Gagal membuat transaksi. Coba lagi nanti.');
        }
    }

    /**
     * Langkah 4: Callback dari Midtrans
     */
    public function notification(Request $request)
    {
        $payload = $request->json()->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        // Cari booking
        $booking = Booking::where('order_id', $orderId)->first();
        if (!$booking) return response('Booking not found', 404);

        // Update status
        if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
            $booking->update(['status' => 'dibayar']);
        } elseif ($transactionStatus == 'settlement') {
            $booking->update(['status' => 'dibayar']);
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'expire') {
            $booking->update(['status' => 'dibatalkan']);
        }

        return response('OK', 200);
    }

    /**
     * Langkah 5: Redirect setelah pembayaran
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $booking = Booking::where('order_id', $orderId)->firstOrFail();

        return redirect()->route('booking.show', $booking)
                        ->with('success', 'Pembayaran berhasil! Booking Anda dikonfirmasi.');
    }

    public function error(Request $request)
    {
        return redirect()->route('booking.index')
                        ->withErrors('Pembayaran dibatalkan atau gagal.');
    }
}