<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Voucher;
use App\Models\VoucherUsage; // if created
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function checkoutForm()
    {
        $cart = Cart::firstOrCreate(['user_id'=>auth()->id]);
        $cart->load('items');
        return view('booking.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'contact_phone'=>'required',
            'notes'=>'nullable',
            // add any booking fields (date/time) you need
        ]);

        $cart = Cart::where('user_id', auth()->id)->with('items')->first();
        if (!$cart || $cart->items->isEmpty()) return back()->with('error','Keranjang kosong');

        DB::beginTransaction();
        try {
            $subtotal = $cart->total;
            $discount = $cart->discount ?? 0;
            $total = $subtotal - $discount;

            $booking = Booking::create([
                'user_id'=>auth()->id,
                'subtotal'=>$subtotal,
                'discount'=>$discount,
                'total'=>$total,
                'voucher_id'=>$cart->voucher_id,
                'status'=>'pending',
                'meta'=>json_encode(['phone'=>$request->contact_phone,'notes'=>$request->notes])
            ]);

            foreach ($cart->items as $ci) {
                BookingItem::create([
                    'booking_id'=>$booking->id,
                    'item_id'=>$ci->item_id,
                    'item_type'=>$ci->item_type,
                    'qty'=>$ci->qty,
                    'price'=>$ci->price,
                    'meta'=>null, // you can store chosen jam/time here for lapangan
                ]);
            }

            // reduce voucher quota & register usage
            if ($cart->voucher_id) {
                $voucher = Voucher::find($cart->voucher_id);
                if ($voucher) {
                    if ($voucher->quota !== null) $voucher->decrement('quota');
                    // record usage if table exists
                    if (class_exists(\App\Models\VoucherUsage::class)) {
                        \App\Models\VoucherUsage::create([
                            'voucher_id'=>$voucher->id,
                            'user_id'=>auth()->id,
                            'booking_id'=>$booking->id
                        ]);
                    }
                }
            }

            // clear cart
            $cart->items()->delete();
            $cart->delete();

            DB::commit();
            return redirect()->route('home')->with('success','Booking berhasil dibuat!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
