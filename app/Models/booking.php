<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'lapangan_id', 'tanggal', 'jam_mulai', 'jam_selesai', 
        'durasi', 'total_harga', 'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'total_harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    // Scope untuk cek ketersediaan
    public function scopeAvailable($query, $lapanganId, $tanggal, $jamMulai, $durasi)
    {
        $jamSelesai = date('H:i', strtotime("$jamMulai +{$durasi} hours"));
        
        return $query->where('lapangan_id', $lapanganId)
                    ->where('tanggal', $tanggal)
                    ->where(function ($q) use ($jamMulai, $jamSelesai) {
                        $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                          ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                          ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                              $q2->where('jam_mulai', '<=', $jamMulai)
                                 ->where('jam_selesai', '>=', $jamSelesai);
                          });
                    })->doesntExist();
    }
}