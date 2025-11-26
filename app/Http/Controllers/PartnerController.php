<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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
        $partnerId = $partner->id;

        // Data statistik
        $totalLapangan = \App\Models\Lapangan::where('partner_id', $partnerId)->count();
        $totalBooking = \App\Models\Booking::whereHas('lapangan', fn($q) => $q->where('partner_id', $partnerId))->count();
        $confirmedBookings = \App\Models\Booking::whereHas('lapangan', fn($q) => $q->where('partner_id', $partnerId))
            ->where('partner_status', 'dikonfirmasi')
            ->count();
        $pendingConfirmations = \App\Models\Booking::whereHas('lapangan', fn($q) => $q->where('partner_id', $partnerId))
            ->where('status', 'dibayar')
            ->where('partner_status', 'menunggu_konfirmasi')
            ->count();
        $pendapatan = \App\Models\Booking::whereHas('lapangan', fn($q) => $q->where('partner_id', $partnerId))
            ->where('partner_status', 'dikonfirmasi')
            ->sum('total_harga');

        $latestBookings = \App\Models\Booking::with(['user', 'lapangan'])
            ->whereHas('lapangan', fn($q) => $q->where('partner_id', $partnerId))
            ->where('status', 'dibayar')
            ->latest()
            ->take(5)
            ->get();

        $bookingsChart = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            
            $pending = \App\Models\Booking::whereDate('created_at', $dateStr)
                ->whereHas('lapangan', fn($q) => $q->where('partner_id', $partnerId))
                ->where('status', 'dibayar')
                ->where('partner_status', 'menunggu_konfirmasi')
                ->count();
                
            $confirmed = \App\Models\Booking::whereDate('created_at', $dateStr)
                ->whereHas('lapangan', fn($q) => $q->where('partner_id', $partnerId))
                ->where('partner_status', 'dikonfirmasi')
                ->count();

            $bookingsChart[] = [
                'date' => $date->format('d M'),
                'pending' => $pending,
                'confirmed' => $confirmed
            ];
        }

        return view('partner.dashboard', compact(
            'totalLapangan',
            'totalBooking',
            'confirmedBookings',
            'pendingConfirmations',
            'pendapatan',
            'latestBookings',
            'bookingsChart'
        ));
    }

    public function showForm()
    {
        if (Auth::user()->role === 'partner') {
            return redirect()->route('partner.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai Mitra.');
        }
        return view('partner.register');
    }

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
     * 🔥 KODE UTAMA: Tampilkan SEMUA BOOKING (tanpa order — aman dari error)
     */
    public function confirmations(Request $request)
    {
        $partnerId = Auth::id();
        $statusFilter = $request->query('status');

        // 🔹 HANYA BOOKING — sama seperti di dashboard(), tapi semua status
        $bookings = Booking::with(['user', 'lapangan'])
            ->whereHas('lapangan', fn($q) => $q->where('partner_id', $partnerId))
            ->where('status', 'dibayar')
            ->when($statusFilter, fn($q) => $q->where('partner_status', $statusFilter))
            ->whereIn('partner_status', ['menunggu_konfirmasi', 'dikonfirmasi', 'ditolak'])
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'type' => 'booking',
                    'reference' => 'BK-' . $booking->id,
                    'customer' => optional($booking->user)->name ?? '—',
                    'item' => optional($booking->lapangan)->nama ?? 'Lapangan tidak ditemukan',
                    'date' => $booking->tanggal,
                    'time' => $booking->jam_mulai . ' - ' . $booking->jam_selesai,
                    'total' => $booking->total_harga,
                    'status' => $booking->partner_status,
                    'catatan' => optional($booking->confirmations()->latest()->first())->catatan ?? '-',
                    'created_at' => $booking->created_at,
                    'model' => $booking,
                ];
            });

        // 🔸 $orders sementara kosong (karena order belum siap)
        $orders = collect();

        $items = $bookings->merge($orders)->sortByDesc('created_at')->values();

        $statusOptions = [
            '' => 'Semua Status',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'ditolak' => 'Ditolak',
        ];

        return view('partner.confirmations', compact('items', 'statusOptions', 'statusFilter'));
    }

    /**
     * Update status booking via dropdown
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['booking'])], // hanya booking dulu
            'id' => 'required|integer',
            'status' => ['required', Rule::in(['menunggu_konfirmasi', 'dikonfirmasi', 'ditolak'])],
            'catatan' => 'nullable|string|max:500',
        ]);

        $partner = Auth::user();
        $booking = Booking::findOrFail($request->id);

        // Validasi kepemilikan
        if ($booking->lapangan->partner_id !== $partner->id) {
            return back()->withErrors(['error' => 'Akses ditolak: Booking bukan milik Anda.']);
        }

        // Update status
        $booking->update(['partner_status' => $request->status]);

        // Log konfirmasi
        BookingConfirmation::create([
            'booking_id' => $booking->id,
            'order_id' => null,
            'partner_id' => $partner->id,
            'type' => 'booking',
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', '✅ Status booking berhasil diubah.');
    }

    public function leave()
    {
        $user = Auth::user();
        $user->update(['role' => 'user']);

        return redirect()->route('home')
            ->with('success', '👋 Anda telah keluar dari mode partner.');
    }
}