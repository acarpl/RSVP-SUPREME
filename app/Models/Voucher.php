<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'code',
        'description',
        'type',
        'value',
        'min_amount',
        'max_discount',
        'quota',
        'expires_at',
    ];
}
