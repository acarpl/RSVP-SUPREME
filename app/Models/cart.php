<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id','total','voucher_id','discount'];

    public function items() { return $this->hasMany(CartItem::class); }
    public function voucher() { return $this->belongsTo(Voucher::class); }

    public function updateTotal()
    {
        $this->load('items.item'); // eager load
        $subtotal = $this->items->sum(fn($i) => $i->price * $i->qty);
        $this->total = $subtotal;
        $this->discount = $this->voucher ? $this->voucher->calculateDiscount($subtotal) : 0;
        $this->save();
    }
}
