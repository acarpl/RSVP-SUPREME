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
        $product = Product::findOrFail($productId); // auto 404 jika tidak ada
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity < 1 || $quantity > $product->stock) {
            return back()->withErrors(['Jumlah tidak valid atau melebihi stok.']);
        }

        $cart = session('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;

        // Batasi maksimal stok
        if ($cart[$productId] > $product->stock) {
            $cart[$productId] = $product->stock;
        }

        session(['cart' => $cart]);

        return back()->with('success', '✅ ' . $product->name . ' ditambahkan ke keranjang.');
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
        return back();
    }

    public function remove($productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);
        return back()->with('success', 'Barang dihapus dari keranjang.');
    }

    public function count()
    {
        $cart = session('cart', []);
        $count = array_sum($cart);
        return response()->json(['count' => $count]);
    }
}