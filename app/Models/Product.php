<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'partner_id',
        'name',
        'description',
        'price',
        'category',
        'stock',
        'image',
        'status',
    ];

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
    use HasFactory;
}
