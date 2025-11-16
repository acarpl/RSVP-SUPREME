<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id','subtotal','discount','total','voucher_id','status','meta'];
    public function items() { return $this->hasMany(BookingItem::class); }
}


