<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // 🔥 WAJIB! agar auth()->id() tidak merah

class ProductController extends Controller
{
    // ============================
    // USER — LIST PRODUK
    // ============================
    public function index()
    {
        $products = Product::latest()->paginate(12);
        return view('produk.index', compact('products'));
    }

    public function alat()
    {
        $products = Product::where('category', 'alat')->get();
        return view('produk.alat', compact('products'));
    }

    public function makanan()
    {
        $products = Product::where('category', 'makanan')->get();
        return view('produk.makanan', compact('products'));
    }

    // ============================
    // USER — DETAIL PRODUK
    // ============================
    public function show(Product $product)
    {
        return view('produk.show', compact('product'));
    }

    // ============================
    // PARTNER — MANAGE
    // ============================
    public function manage()
    {
        $products = Product::where('partner_id', Auth::id()) // 🔥 FIX → pakai Auth::id()
                            ->latest()
                            ->get();

        return view('produk.manage', compact('products'));
    }

    // Create form
    public function create()
    {
        return view('produk.create');
    }

    // ============================
    // PARTNER — STORE PRODUK
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'stock' => 'required|integer',
            'image' => 'image|mimes:jpg,png,jpeg'
        ]);

        $imagePath = $request->file('image')?->store('products', 'public');

        Product::create([
            'partner_id' => Auth::id(), // 🔥 FIX
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'stock' => $request->stock,
            'image' => $imagePath
        ]);

        return redirect()->route('products.manage')->with('success', 'Produk berhasil ditambahkan!');
    }

    // ============================
    // EDIT PRODUK
    // ============================
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);
        return view('produk.edit', compact('product'));
    }

    // Update produk
    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'stock' => 'required|integer',
            'image' => 'image|mimes:jpg,png,jpeg'
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'stock' => $request->stock,
            'image' => $imagePath
        ]);

        return redirect()->route('products.manage')->with('success', 'Produk berhasil diperbarui!');
    }

    // ============================
    // HAPUS PRODUK
    // ============================
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        if ($product->image) Storage::disk('public')->delete($product->image);

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }

    // ============================
    // HAK AKSES PRODUK
    // ============================
    private function authorizeProduct(Product $product)
    {
        if ($product->partner_id !== Auth::id()) { // 🔥 FIX
            abort(403);
        }
    }
}
