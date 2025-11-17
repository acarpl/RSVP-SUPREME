<?php

// app/Models/Venue.php
class Venue extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'user_id']; // user_id → mitra

    public function lapangans()
    {
        return $this->hasMany(Lapangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}