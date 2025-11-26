<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // SQLite tidak support ALTER COLUMN enum → rebuild with new values

        // 1. Rename tabel lama
        Schema::rename('bookings', 'bookings_old');

        // 2. Buat ulang dengan enum baru
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lapangan_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('durasi');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', [
                'menunggu_pembayaran',
                'dibayar',
                'dibatalkan',
                'kadaluarsa'
            ])->default('dibayar');
            $table->string('order_id')->nullable()->unique();
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });

        // 3. Migrasi data lama (jika ada)
        DB::statement("
            INSERT INTO bookings (
                id, user_id, lapangan_id, tanggal, jam_mulai, jam_selesai,
                durasi, total_harga, status, order_id, snap_token, created_at, updated_at
            )
            SELECT
                id, user_id, lapangan_id, tanggal, jam_mulai, jam_selesai,
                durasi, total_harga,
                CASE
                    WHEN status = 'menunggu' THEN 'menunggu_pembayaran'
                    WHEN status = 'dikonfirmasi' THEN 'dibayar'
                    WHEN status = 'selesai' THEN 'dibayar'
                    WHEN status = 'dibatalkan' THEN 'dibatalkan'
                    ELSE 'menunggu_pembayaran'
                END,
                NULL, NULL, created_at, updated_at
            FROM bookings_old
        ");

        // 4. Hapus tabel lama
        Schema::dropIfExists('bookings_old');
    }

    public function down()
    {
        // Opsional: kembalikan ke enum lama
    }
};