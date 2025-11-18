<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    // List user's bookings
    public function index()
    {
        $user = Auth::user();
        $bookings = $user ? Booking::where('user_id', $user->id)->latest()->get() : collect([]);
        return view('booking.index', compact('bookings'));
    }

    // Show order now form for a field
    public function orderNow(Lapangan $lapangan)
    {
        return view('booking.order-now', compact('lapangan'));
    }

    // Helper: check availability (no overlapping bookingitems)
    private function isAvailable($lapangan_id, $start, $end)
    {
        return BookingItem::where('lapangan_id', $lapangan_id)
            ->where(function($q) use ($start, $end) {
                // overlap conditions
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function($qq) use ($start, $end) {
                      $qq->where('start_time', '<=', $start)
                         ->where('end_time', '>=', $end);
                  });
            })->count() == 0;
    }

    // Store booking directly (order now)
    public function storeOrderNow(Request $request, Lapangan $lapangan)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $start = $request->input('start_time');
        $end = $request->input('end_time');

        // availability check
        if (! $this->isAvailable($lapangan->id, $start, $end)) {
            return back()->with('error','Lapangan tidak tersedia pada rentang waktu tersebut. Silakan pilih waktu lain.');
        }

        DB::beginTransaction();
        try {
            $hours = max(1, ceil((strtotime($end) - strtotime($start)) / 3600));
            $total = ($lapangan->price_per_hour ?? 0) * $hours;

            $booking = Booking::create([
                'user_id' => $user?->id,
                'status' => 'pending',
                'total' => $total,
                'notes' => $request->input('notes'),
            ]);

            BookingItem::create([
                'booking_id' => $booking->id,
                'lapangan_id' => $lapangan->id,
                'start_time' => $start,
                'end_time' => $end,
                'price' => $lapangan->price_per_hour,
                'quantity' => 1,
            ]);

            DB::commit();
            return redirect()->route('booking.show', $booking->id)->with('success', 'Booking berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    // Show create from cart form
    public function createFromCart()
    {
        $cart = session('cart', []);
        $products = array_values($cart);
        return view('booking.from-cart-blade', compact('products'));
    }

    // Store booking from cart (checkout)
    public function storeFromCart(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error','Keranjang kosong.');
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $total = collect($cart)->sum(fn($p) => $p['price'] * $p['quantity']);

            // availability check for each item with time
            foreach ($cart as $item) {
                $lapangan_id = $item['id'] ?? null;
                $start = $item['start_time'] ?? null;
                $end = $item['end_time'] ?? null;
                if ($lapangan_id && $start && $end) {
                    if (! $this->isAvailable($lapangan_id, $start, $end)) {
                        DB::rollBack();
                        return back()->with('error', 'Salah satu lapangan di keranjang tidak tersedia pada jadwalnya.');
                    }
                }
            }

            $booking = Booking::create([
                'user_id' => $user?->id,
                'status' => 'pending',
                'total' => $total,
                'notes' => $request->input('notes'),
            ]);

            foreach ($cart as $item) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'lapangan_id' => $item['id'] ?? null,
                    'start_time' => $item['start_time'] ?? null,
                    'end_time' => $item['end_time'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            // clear session cart
            session()->forget('cart');

            DB::commit();
            return redirect()->route('booking.show', $booking->id)->with('success','Booking berhasil dibuat dari keranjang.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage());
        }
    }

    // Show booking detail / checkout
    public function show(Booking $booking)
    {
        return view('booking.show', compact('booking'));
    }

    // Checkout page (if payment integration)
    public function checkout(Booking $booking)
    {
        return view('booking.checkout', compact('booking'));
    }

    // Confirm booking (mark as paid/confirmed)
    public function confirm(Request $request, Booking $booking)
    {
        // For payment integration: here you'd verify gateway callback.
        // For now, simple mark as confirmed.
        $booking->status = 'confirmed';
        $booking->save();
        return redirect()->route('booking.show', $booking->id)->with('success','Booking dikonfirmasi (dibayar).');
    }
}
