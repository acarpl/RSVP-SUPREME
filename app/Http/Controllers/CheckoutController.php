<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Lapangan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // Tampilkan halaman checkout dari keranjang
public function index()
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
    }

    $items = [];
    $total = 0;

    foreach ($cart as $productId => $quantity) {
        $product = Product::find($productId);
        if ($product) {
            $subtotal = $product->price * $quantity;
            $items[] = compact('product', 'quantity', 'subtotal');
            $total += $subtotal;
        }
    }

    if (empty($items)) {
        return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    return view('checkout.index', compact('items', 'total'));
}
    // Proses checkout
    public function store(Request $request)
    {

        Log::info('Checkout request data:', $request->all());
        Log::info('Cart:', session('cart', []));

        $request->validate([
            'jenis_pesanan' => 'required|in:beli_produk,sewa_alat',
            'alamat' => 'required_if:jenis_pesanan,beli_produk|string|max:500',
            'tanggal' => 'required_if:jenis_pesanan,sewa_alat|date|after_or_equal:today',
            'jam_mulai' => 'required_if:jenis_pesanan,sewa_alat|date_format:H:i',
            'durasi' => 'required_if:jenis_pesanan,sewa_alat|integer|min:1|max:12',
        ]);

        Log::info('✅ Validation passed');

        $jamSelesai = null;

        // Buat booking
        $booking = Booking::create([
    'user_id' => Auth::id(),
    'jenis_pesanan' => $request->jenis_pesanan,
    'lapangan_id' => null,
    'tanggal' => $request->jenis_pesanan === 'sewa_alat' ? $request->tanggal : null, // tetap null untuk beli_produk
    'jam_mulai' => $request->jenis_pesanan === 'sewa_alat' ? $request->jam_mulai . ':00' : null,
    'jam_selesai' => $request->jenis_pesanan === 'sewa_alat'
        ? date('H:i:s', strtotime("{$request->jam_mulai} +{$request->durasi} hours"))
        : null,
    'durasi' => $request->jenis_pesanan === 'sewa_alat' ? $request->durasi : null,
    'alamat' => $request->jenis_pesanan === 'beli_produk' ? $request->alamat : null,
    'total_harga' => 0,
    'status' => 'menunggu_pembayaran',
]);

        // Tambahkan produk dari keranjang
        $cart = session('cart', []);
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'type' => 'product',
                    'bookable_id' => $product->id,
                    'bookable_type' => Product::class,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                ]);
                $booking->total_harga += $product->price * $quantity;
            }
        }

        $booking->save();

        // Kosongkan keranjang
        session()->forget('cart');
        session()->forget('cart_count');

        // Redirect ke Midtrans
        return redirect()->route('payment.process', $booking);
    }
}
