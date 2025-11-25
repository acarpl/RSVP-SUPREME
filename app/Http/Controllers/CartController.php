<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
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

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, $productId)
{
    $product = Product::find($productId);

    if (!$product) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        }
        return back()->withErrors(['Produk tidak ditemukan.']);
    }

    $quantity = (int) $request->input('quantity', 1);

    if ($quantity < 1 || $quantity > $product->stock) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah tidak valid atau melebihi stok.'
            ], 400);
        }
        return back()->withErrors(['Jumlah tidak valid atau melebihi stok.']);
    }

    // Tambahkan ke sesi keranjang
    $cart = session('cart', []);
    $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;

    if ($cart[$productId] > $product->stock) {
        $cart[$productId] = $product->stock;
    }

    session(['cart' => $cart]);
    session(['cart_count' => array_sum($cart)]);

    // ⭐ INI YANG MEMPERBAIKI AJAX ⭐
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan ke keranjang.',
            'cart_count' => array_sum($cart)
        ]);
    }

    return back()->with('success', 'Berhasil menambahkan ke keranjang.');
}

    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        $product = Product::find($productId);
        if (!$product) return back();

        $cart = session('cart', []);
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = min($quantity, $product->stock);
        }

        session(['cart' => $cart]);
        session(['cart_count' => array_sum($cart)]); // ✅ Update count

        return back();
    }

    public function remove($productId)
    {
        $cart = session('cart', []);
        $product = Product::find($productId);
        $name = $product ? $product->name : 'Produk';

        unset($cart[$productId]);
        session(['cart' => $cart]);
        session(['cart_count' => array_sum($cart)]); // ✅ Update count

        return response()->json(['success' => true]);
    }

    public function count()
    {
        $cart = session('cart', []);
        $count = array_sum($cart);
        $total = 0;
        $items = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal = $product->price * $quantity;
                $items[] = [
                    'id' => $productId,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity
                ];
                $total += $subtotal;
            }
        }

        return response()->json([
            'total_items' => $count,
            'total_price' => $total,
            'items' => $items
        ]);
    }
/**
 * Menampilkan halaman checkout dari keranjang
 */
public function checkout()
{
    $cart = session('cart', []);
    
    if (empty($cart)) {
        return redirect()->route('products.index')->with('error', 'Keranjang kosong.');
    }

    $items = [];
    $total = 0;
    $lapangans = \App\Models\Lapangan::where('status', 'aktif')->get();

    foreach ($cart as $productId => $quantity) {
        $product = Product::find($productId);
        if ($product) {
            $subtotal = $product->price * $quantity;
            $items[] = compact('product', 'quantity', 'subtotal');
            $total += $subtotal;
        }
    }

    return view('cart.checkout', compact('items', 'total', 'lapangans'));
}

/**
 * Memproses checkout dari keranjang
 */
public function processCheckout(Request $request)
{
    $request->validate([
        'lapangan_id' => 'required|exists:lapangans,id',
        'tanggal' => 'required|date|after_or_equal:today',
        'jam_mulai' => 'required|date_format:H:i',
        'durasi' => 'required|integer|min:1|max:12',
    ]);

    $lapangan = \App\Models\Lapangan::findOrFail($request->lapangan_id);
    $jamSelesai = date('H:i', strtotime("{$request->jam_mulai} +{$request->durasi} hours"));

    // Buat booking
    $booking = \App\Models\Booking::create([
        'user_id' => auth()->id(),
        'lapangan_id' => $lapangan->id,
        'tanggal' => $request->tanggal,
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $jamSelesai,
        'durasi' => $request->durasi,
        'total_harga' => $lapangan->harga * $request->durasi,
        'status' => 'menunggu_pembayaran',
    ]);

    // Tambahkan produk dari keranjang ke booking_items
    $cart = session('cart', []);
    foreach ($cart as $productId => $quantity) {
        $product = Product::find($productId);
        if ($product) {
            $booking->items()->create([
                'type' => 'product',
                'bookable_id' => $product->id,
                'bookable_type' => Product::class,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
            ]);
            // Update total harga
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