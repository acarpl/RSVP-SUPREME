<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $cart = $this->prepareCartData($cart);
        return view('cart.index', compact('cart'));
    }

public function add(Request $request)
{
    // ✅ Validasi ketat
    $request->validate([
        'product_id' => 'required|integer|min:1',
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:1',
    ]);

    try {
        // ✅ Ambil data lama
        $cart = session('cart', []);
        
        // ✅ Pastikan $cart array
        if (!is_array($cart)) {
            $cart = [];
        }

        $id = (int) $request->product_id;

        // ✅ Tambahkan ke cart
        $cart[$id] = [
            'id' => $id,
            'name' => trim($request->name),
            'price' => (float) $request->price,
            'quantity' => (int) ($request->quantity ?? 1),
            'created_at' => now()->toDateTimeString(),
        ];

        // ✅ Simpan
        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan',
            'cart_count' => collect($cart)->sum('quantity')
        ]);

    } catch (\Exception $e) {
        // ✅ Tangkap semua error
        \Log::error('Cart add failed', [
            'input' => $request->all(),
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => basename($e->getFile()),
        ]);

        return response()->json([
            'success' => false,
            'error' => 'Gagal menambahkan ke keranjang',
            'debug' => app()->isLocal() ? $e->getMessage() : null
        ], 500);
    }
}
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|integer']);
        
        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'action' => 'required|in:increase,decrease'
        ]);

        $cart = session('cart', []);
        $id = $request->product_id;

        if (!isset($cart[$id])) {
            return response()->json(['success' => false, 'error' => 'Produk tidak ditemukan']);
        }

        if ($request->action === 'increase') {
            $cart[$id]['quantity']++;
        } elseif ($request->action === 'decrease') {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }
        }

        session(['cart' => $cart]);
        return response()->json(['success' => true, 'cart' => $this->prepareCartData($cart)]);
    }

    public function count()
    {
        $cart = session('cart', []);
        $cart = $this->prepareCartData($cart);
        
        $totalItems = collect($cart)->sum('quantity');
        $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return response()->json([
            'total_items' => $totalItems,
            'total_price' => $totalPrice,
            'items' => $cart,
        ]);
    }

    private function prepareCartData($cart)
    {
        return array_values($cart);
    }
}