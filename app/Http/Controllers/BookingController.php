<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Lapangan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // 📋 Daftar booking user
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with('lapangan')
            ->latest()
            ->paginate(10);

        return view('booking.index', compact('bookings'));
    }

    // ⚡ Booking langsung (Order Now)
    public function orderNow($lapanganId)
    {
        $lapangan = Lapangan::findOrFail($lapanganId);
        $products = Product::where('stock', '>', 0)->get();

        return view('booking.order-now', compact('lapangan', 'products'));
    }

    // 💾 Simpan booking langsung
    public function storeOrderNow(Request $request, $lapanganId)
    {
        $request->validate([
            'start_time' => 'required|date|after:now',
            'duration_hours' => 'required|integer|min:1|max:12',
        ]);

        $lapangan = Lapangan::findOrFail($lapanganId);
        $startTime = \Carbon\Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addHours($request->duration_hours);

        return DB::transaction(function () use ($request, $lapangan, $startTime, $endTime) {
            // Hitung harga dasar
            $basePrice = $lapangan->price_per_hour * $request->duration_hours;
            $total = $basePrice;

            // Buat booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'lapangan_id' => $lapangan->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $request->duration_hours,
                'total_price' => $total,
                'status' => 'pending',
            ]);

            // Simpan item lapangan
            $booking->items()->create([
                'type' => 'lapangan',
                'bookable_id' => $lapangan->id,
                'bookable_type' => Lapangan::class,
                'name' => $lapangan->nama,
                'price' => $lapangan->price_per_hour,
                'quantity' => $request->duration_hours,
            ]);

            // Simpan produk (jika dipilih)
            if ($request->has('products')) {
                foreach ($request->products as $productId) {
                    $product = Product::find($productId);
                    if ($product && $product->stock > 0) {
                        $booking->items()->create([
                            'type' => 'product',
                            'bookable_id' => $product->id,
                            'bookable_type' => Product::class,
                            'name' => $product->name,
                            'price' => $product->price,
                            'quantity' => 1,
                        ]);
                        $total += $product->price;
                    }
                }
                $booking->update(['total_price' => $total]);
            }

            return redirect()->route('booking.checkout', $booking)
                ->with('success', 'Booking berhasil! Silakan lanjut ke pembayaran.');
        });
    }

    // 🛒 Booking dari keranjang
    public function createFromCart()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang kosong.');
        }

        $lapangans = Lapangan::all();
        $products = collect($cart)->mapWithKeys(function ($item) {
            return [$item['id'] => $item];
        });

        return view('booking.from-cart', compact('lapangans', 'products'));
    }

    // 💾 Simpan booking dari keranjang
    public function storeFromCart(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'start_time' => 'required|date|after:now',
            'duration_hours' => 'required|integer|min:1|max:12',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang kosong.');
        }

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $startTime = \Carbon\Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addHours($request->duration_hours);

        return DB::transaction(function () use ($request, $lapangan, $startTime, $endTime, $cart) {
            $basePrice = $lapangan->price_per_hour * $request->duration_hours;
            $total = $basePrice;

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'lapangan_id' => $lapangan->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $request->duration_hours,
                'total_price' => $total,
                'status' => 'pending',
            ]);

            // Item lapangan
            $booking->items()->create([
                'type' => 'lapangan',
                'bookable_id' => $lapangan->id,
                'bookable_type' => Lapangan::class,
                'name' => $lapangan->nama,
                'price' => $lapangan->price_per_hour,
                'quantity' => $request->duration_hours,
            ]);

            // Item produk dari keranjang
            foreach ($cart as $item) {
                $booking->items()->create([
                    'type' => 'product',
                    'bookable_id' => $item['id'],
                    'bookable_type' => Product::class,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
                $total += $item['price'] * $item['quantity'];
            }

            $booking->update(['total_price' => $total]);
            session()->forget('cart'); // Kosongkan keranjang

            return redirect()->route('booking.checkout', $booking)
                ->with('success', 'Booking berhasil! Silakan lanjut ke pembayaran.');
        });
    }

    // 💳 Halaman checkout
    public function checkout(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('booking.checkout', compact('booking'));
    }

    // 🎫 Detail booking
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('booking.show', compact('booking'));
    }

    public function confirm(Booking $booking)
{
    $booking->update(['status' => 'confirmed']);
    return response()->json(['success' => true]);
}
}