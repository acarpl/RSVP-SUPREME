<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'location', 'harga_per jam', 'capacity', 'image',
        'venue_id', // tambahkan ini jika ada kolom venue_id
    ];

    // 🔹 Tambahkan relasi jika venue tersedia
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    // 🔹 Opsional: relasi ke bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // 🔹 Opsional: relasi ke produk (jika 1 lapangan punya produk)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}