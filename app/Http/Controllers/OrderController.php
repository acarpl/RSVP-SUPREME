<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pesanan' => 'required|in:beli_produk,sewa_alat',
            'alamat_pengiriman' => 'required_if:jenis_pesanan,beli_produk|string|max:500',
            'tanggal' => 'required_if:jenis_pesanan,sewa_alat|date|after_or_equal:today',
            'jam_mulai' => 'required_if:jenis_pesanan,sewa_alat|date_format:H:i',
            'durasi' => 'required_if:jenis_pesanan,sewa_alat|integer|min:1|max:12',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['Keranjang kosong.']);
        }

        // Hitung total
        $total = 0;
        foreach ($cart as $productId => $qty) {
            $product = Product::find($productId);
            if ($product) $total += $product->price * $qty;
        }

        if ($total <= 0) {
            return back()->withErrors(['total tidak valid.']);
        }

        // Buat order
        $orderNumber = 'OD-' . now()->format('Ymd') . '-' . Str::padLeft(Order::count() + 1, 3, '0');
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => $orderNumber,
            'jenis_pesanan' => $request->jenis_pesanan,
            'alamat_pengiriman' => $request->jenis_pesanan === 'beli_produk' ? $request->alamat_pengiriman : null,
            'tanggal' => $request->jenis_pesanan === 'sewa_alat' ? $request->tanggal : null,
            'jam_mulai' => $request->jenis_pesanan === 'sewa_alat' ? $request->jam_mulai . ':00' : null,
            'durasi' => $request->jenis_pesanan === 'sewa_alat' ? $request->durasi : null,
            'total' => $total,
            'status' => 'menunggu_pembayaran',
        ]);

        // Tambah item
        foreach ($cart as $productId => $qty) {
            $product = Product::find($productId);
            if ($product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $qty,
                ]);
            }
        }

        // Kosongkan keranjang
        session()->forget(['cart', 'cart_count']);

        // Redirect ke pembayaran
        return redirect()->route('order.payment', $order);
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'menunggu_pembayaran') {
            abort(403);
        }

        // Gunakan PaymentController@process tapi dengan Order
        // Atau buat method sendiri di PaymentController — saya bantu sesuaikan
        return app(PaymentController::class)->processOrder($order);
    }
}