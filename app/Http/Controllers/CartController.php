<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $items = $this->formatCart($cart);
        $totalItems = collect($items)->sum('quantity');
        $totalPrice = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);

        return view('cart.index', compact('items', 'totalItems', 'totalPrice'));
    }

    public function add(Request $request)
    {
        // Ambil data dari form (FormData)
        $id = (int) $request->input('product_id', $request->input('id', 0));
        $name = trim($request->input('name', 'Item'));
        $price = (float) $request->input('price', 0);
        $quantity = (int) $request->input('quantity', 1);

        // Validasi minimal
        if ($id <= 0 || $price < 0 || $quantity <= 0) {
            return response()->json([
                'success' => false,
                'error' => 'Data tidak lengkap',
                'debug' => compact('id', 'name', 'price', 'quantity')
            ], 400);
        }

        // Simpan ke session
        $cart = session('cart', []);
        $cart[$id] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => ($cart[$id]['quantity'] ?? 0) + $quantity,
        ];
        session(['cart' => $cart]);

        $totalItems = collect($cart)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Berhasil ditambahkan ke keranjang',
            'cart_count' => $totalItems,
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session('cart', []);
        $id = (int) $request->input('product_id');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }
        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);
        $id = (int) $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        if (isset($cart[$id])) {
            if ($quantity <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $quantity;
            }
            session(['cart' => $cart]);
        }
        return response()->json(['success' => true]);
    }

    public function count()
    {
        $cart = session('cart', []);
        $items = $this->formatCart($cart);
        $totalItems = collect($items)->sum('quantity');
        $totalPrice = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);

        return response()->json([
            'total_items' => $totalItems,
            'total_price' => $totalPrice,
            'items' => $items,
        ]);
    }

    private function formatCart($cart)
    {
        return collect($cart)->map(function ($item, $id) {
            return [
                'id' => (int) $id,
                'name' => $item['name'] ?? 'Item',
                'price' => (float) ($item['price'] ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 1),
            ];
        })->values()->all();
    }
}