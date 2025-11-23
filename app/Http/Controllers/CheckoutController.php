<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Midtrans config (sama seperti di PaymentController)
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        if (app()->environment('local')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ];
        }
    }

    // Tampilkan halaman checkout (isi alamat, dll)
    public function index()
    {
        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->withErrors('Keranjang kosong.');
        }

        // Ambil data produk + hitung total
        $items = [];
        $total = 0;
        foreach ($cartItems as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product && $product->stock >= $quantity) {
                $subtotal = $product->price * $quantity;
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        if (empty($items)) {
            return back()->withErrors('Produk tidak tersedia atau stok habis.');
        }

        return view('checkout.index', compact('items', 'total'));
    }

    // Proses checkout → buat order → redirect ke Midtrans
    public function store(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return back()->withErrors('Keranjang kosong.');
        }

        // Hitung total & validasi stok
        $items = [];
        $total = 0;
        foreach ($cartItems as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product || $product->stock < $quantity) {
                return back()->withErrors("Stok {$product->name} tidak cukup.");
            }
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $product->price,
            ];
            $total += $product->price * $quantity;
        }

        // Buat order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => Order::generateOrderNumber(),
            'alamat' => $request->alamat,
            'catatan' => $request->catatan,
            'total' => $total,
            'status' => 'menunggu_pembayaran',
        ]);

        // Simpan order items
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Kurangi stok
            $item['product']->decrement('stock', $item['quantity']);
        }

        // Kosongkan keranjang
        session()->forget('cart');

        // Redirect ke Midtrans
        return redirect()->route('checkout.payment', $order);
    }

    // Redirect ke Midtrans (Snap)
    public function payment(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'menunggu_pembayaran') {
            abort(403);
        }

        $orderId = 'SPORTY-ORD-' . $order->id . '-' . now()->format('His');
        $order->update(['order_id_midtrans' => $orderId]);

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $order->total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?: '081234567890',
                'billing_address' => [
                    'first_name' => Auth::user()->name,
                    'address' => $order->alamat,
                    'city' => 'Kota',
                    'postal_code' => '12345',
                    'country_code' => 'ID',
                ],
            ],
            'item_details' => $order->items->map(function ($item) {
                return [
                    'id' => 'prod-' . $item->product_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                ];
            })->toArray(),
            'finish_redirect_url' => route('checkout.finish', $order),
            'unfinish_redirect_url' => route('checkout.error'),
            'error_redirect_url' => route('checkout.error'),
        ];

        try {
            $snap = \Midtrans\Snap::createTransaction($payload);
            return redirect()->away($snap->redirect_url);
        } catch (\Exception $e) {
            Log::error('Checkout Snap Error', ['error' => $e->getMessage()]);
            return back()->withErrors('Gagal membuka pembayaran: ' . $e->getMessage());
        }
    }

    // Setelah bayar (manual)
    public function finish(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        try {
            $status = Transaction::status($order->order_id_midtrans);
            $ts = $status->transaction_status ?? 'unknown';
            $fs = $status->fraud_status ?? 'accept';

            $newStatus = 'menunggu_pembayaran';
            $paymentStatus = 'pending';

            if (in_array($ts, ['capture', 'settlement']) && $fs === 'accept') {
                $newStatus = 'dibayar';
                $paymentStatus = 'settlement';
            } elseif (in_array($ts, ['cancel', 'expire'])) {
                $newStatus = 'dibatalkan';
                $paymentStatus = 'cancel';
            }

            $order->update([
                'status' => $newStatus,
                'payment_status' => $paymentStatus,
            ]);

            return redirect()->route('order.show', $order)
                ->with('success', '✅ Pembayaran produk berhasil!');
        } catch (\Exception $e) {
            return back()->withErrors('Gagal verifikasi pembayaran.');
        }
    }

    public function error()
    {
        return redirect()->route('cart.index')
            ->with('error', '❌ Pembayaran dibatalkan.');
    }

    // Halaman detail order
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        
        if (request()->ajax()) {
            return response()->json(['status' => $order->status]);
        }
    
        return view('order.show', compact('order'));
    }
}