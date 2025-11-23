<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'alamat', 'catatan', 'status',
        'payment_method', 'payment_status', 'total', 'order_id_midtrans'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper: generate order number
    public static function generateOrderNumber()
    {
        return 'SPORTY-ORD-' . now()->format('Ymd') . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
    }
}