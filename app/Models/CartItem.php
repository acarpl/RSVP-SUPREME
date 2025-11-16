<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id','item_id','item_type','qty','price'];

    public function cart() { return $this->belongsTo(Cart::class); }

    // morph-like accessor (we don't define morphTo relationship tables; we'll resolve manually)
    public function item()
    {
        if ($this->item_type === 'product') {
            return \App\Models\Product::find($this->item_id);
        }
        return \App\Models\Lapangan::find($this->item_id);
    }
}
