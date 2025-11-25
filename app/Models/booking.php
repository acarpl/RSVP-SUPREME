<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'durasi',
        'total_harga',
        'order_id',
        'snap_token',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',       // ✅ Casting wajib
        'jam_mulai' => 'datetime', // ✅ Hindari error "Call to format() on null"
        'jam_selesai' => 'datetime', // ✅
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
    public function items()
{
    return $this->hasMany(BookingItem::class);
}
}