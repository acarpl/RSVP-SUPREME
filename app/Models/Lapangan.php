<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    // ✅ WAJIB ADA: daftar field yang boleh diisi mass assignment
    protected $fillable = [
        'nama',
        'lokasi',      // ✅ harus ada
        'kapasitas',
        'harga',
        'status',
        'gambar',
    ];

    // Opsional: cast tipe data
    protected $casts = [
        'kapasitas' => 'integer',
        'harga' => 'integer',
    ];
}