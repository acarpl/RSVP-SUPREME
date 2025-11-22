<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    // ✅ WAJIB ADA: daftar field yang boleh diisi mass assignment
    protected $fillable = [
        'partner_id',   // ✅ BENAR — bukan user_id
        'nama',
        'lokasi',
        'kapasitas',
        'harga',
        'status',
        'gambar',
    ];

    // Relasi
    public function partner()
{
    return $this->belongsTo(User::class, 'partner_id'); // ✅
}

    // Opsional: cast tipe data
    protected $casts = [
        'kapasitas' => 'integer',
        'harga' => 'integer',
    ];
}