<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use App\Models\BookingConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    /**
     * Tampilkan dashboard partner
     */
    public function dashboard()
    {
        $partner = Auth::user();
        
        // Statistik
        $stats = [
            'bookings_today' => Booking::whereDate('tanggal', now()->toDateString())
                ->whereHas('lapangan', fn($q) => $q->where('partner_id', $partner->id))
                ->count(),
            'pending_confirmations' => Booking::where('status', 'dibayar')
                ->where('partner_status', 'menunggu_konfirmasi')
                ->whereHas('lapangan', fn($q) => $q->where('partner_id', $partner->id))
                ->count(),
        ];

        return view('partner.dashboard', compact('stats'));
    }

    /**
     * Tampilkan form daftar mitra
     */
    public function showForm()
    {
        if (Auth::user()->role === 'partner') {
            return redirect()->route('partner.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai Mitra.');
        }
        return view('partner.register');
    }

    /**
     * Proses pendaftaran mitra
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama_usaha'   => 'required|string|max:255',
            'alamat_usaha' => 'required|string|max:500',
            'telepon'      => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        if ($user->role === 'partner') {
            return redirect()->route('partner.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai Mitra.');
        }

        $user->update([
            'role' => 'partner',
            'nama_usaha' => $request->nama_usaha,
            'address' => $request->alamat_usaha,
            'phone' => $request->telepon,
        ]);

        return redirect()->route('partner.dashboard')
            ->with('success', '✅ Pendaftaran Mitra Berhasil! Selamat, Anda sekarang adalah Mitra Sportykuy.');
    }

    /**
     * Tampilkan daftar booking/order menunggu konfirmasi
     */
    public function confirmations()
    {
        $partnerId = Auth::id();

        // Booking lapangan milik partner yang sudah dibayar & menunggu konfirmasi
        $bookings = Booking::with('user', 'lapangan')
            ->whereHas('lapangan', fn($q) => $q->where('partner_id', $partnerId))
            ->where('status', 'dibayar')
            ->where('partner_status', 'menunggu_konfirmasi')
            ->latest()
            ->paginate(10);

        // Orders (opsional - jika ingin konfirmasi pesanan produk)
        // $orders = Order::with('user', 'items.product')
        //     ->whereHas('items.product', fn($q) => $q->where('partner_id', $partnerId))
        //     ->where('status', 'dibayar')
        //     ->where('partner_status', 'menunggu_konfirmasi')
        //     ->latest()
        //     ->paginate(10);

        return view('partner.confirmations', compact('bookings'));
    }

    /**
     * Proses konfirmasi booking oleh partner
     */
    public function confirmBooking(Request $request, Booking $booking)
    {
        $partner = Auth::user();

        // Validasi kepemilikan
        if ($booking->lapangan->partner_id !== $partner->id) {
            return back()->withErrors(['Akses ditolak: Booking bukan milik Anda.']);
        }

        // Validasi status
        if ($booking->status !== 'dibayar' || $booking->partner_status !== 'menunggu_konfirmasi') {
            return back()->withErrors(['Booking tidak dalam status menunggu konfirmasi.']);
        }

        $request->validate([
            'status' => ['required', Rule::in(['dikonfirmasi', 'ditolak'])],
            'catatan' => 'nullable|string|max:500',
        ]);

        // Update status
        $newStatus = $request->status;
        $booking->update([
            'partner_status' => $newStatus,
            'confirmed_by_partner_id' => $partner->id,
        ]);

        // Simpan log konfirmasi
        BookingConfirmation::create([
            'booking_id' => $booking->id,
            'partner_id' => $partner->id,
            'type' => 'booking',
            'status' => $newStatus,
            'catatan' => $request->catatan,
        ]);

        // 🔔 Kirim notifikasi ke customer (opsional)
        // $booking->user->notify(new \App\Notifications\BookingConfirmedByPartner($booking));

        $message = $newStatus === 'dikonfirmasi' 
            ? '✅ Booking berhasil dikonfirmasi.' 
            : '⚠️ Booking ditolak. Customer telah diberi tahu.';

        return back()->with('success', $message);
    }

    /**
     * Keluar dari mode partner
     */
    public function leave()
    {
        $user = Auth::user();
        $user->update(['role' => 'user']);

        return redirect()->route('home')
            ->with('success', '👋 Anda telah keluar dari mode partner.');
    }
}