<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'name',
        'code',
        'description',
        'type',
        'value',
        'discount_type',
        'discount_value',
        'min_amount',
        'quota',
        'expires_at',
        'valid_from',
        'valid_until',
        'image',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}