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
        'alamat_pengiriman',
        'total_harga',
        'order_id',
        'snap_token',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'string',
        'jam_selesai' => 'string',
    ];

    // ✅ Jenis pesanan
    public function getJenisPesananAttribute()
    {
        if ($this->alamat_pengiriman) {
            return 'beli_produk';
        }
        return 'sewa_alat';
    }

    // ✅ Nama jenis pesanan
    public function getJenisPesananTextAttribute()
    {
        return $this->jenis_pesanan === 'beli_produk' ? 'Beli Produk' : 'Sewa Alat';
    }

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