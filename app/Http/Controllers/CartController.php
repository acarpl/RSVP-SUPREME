<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id]);
        $cart->load('items');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'item_id'=>'required|integer',
            'type'=>'required|in:product,lapangan',
            'price'=>'required|numeric',
            'qty'=>'nullable|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(['user_id'=>auth()->id]);

        // Jika item sudah ada di keranjang -> tambah qty
        $existing = $cart->items()->where('item_id',$request->item_id)->where('item_type',$request->type)->first();
        if ($existing) {
            $existing->increment('qty', $request->qty ?? 1);
        } else {
            $cart->items()->create([
                'item_id'=>$request->item_id,
                'item_type'=>$request->type,
                'qty'=>$request->qty ?? 1,
                'price'=>$request->price,
            ]);
        }

        $cart->updateTotal();

        return back()->with('success','Berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate(['qty'=>'required|integer|min:1']);
        $item->update(['qty'=>$request->qty]);
        $item->cart->updateTotal();
        return back()->with('success','Jumlah berhasil diupdate');
    }

    public function remove(CartItem $item)
    {
        $cart = $item->cart;
        $item->delete();
        $cart->updateTotal();
        return back()->with('success','Item dihapus');
    }

    public function applyVoucher(Request $request)
    {
        $request->validate(['code'=>'required|string']);
        $voucher = Voucher::where('code', $request->code)->first();
        if (!$voucher) return back()->with('error','Voucher tidak ditemukan');
        if ($voucher->expires_at && now()->gt($voucher->expires_at)) return back()->with('error','Voucher expired');
        if ($voucher->quota !== null && $voucher->quota <= 0) return back()->with('error','Voucher sudah habis');

        $cart = Cart::firstOrCreate(['user_id'=>auth()->id]);
        $cart->voucher_id = $voucher->id;
        $cart->discount = $voucher->calculateDiscount($cart->total);
        $cart->save();

        return back()->with('success','Voucher diterapkan');
    }

    public function clear()
    {
        $cart = Cart::where('user_id', auth()->id)->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }
        return back()->with('success','Keranjang dikosongkan');
    }
}
