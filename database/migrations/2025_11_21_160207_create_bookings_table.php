<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('lapangan_id')->constrained()->onDelete('cascade');
        $table->date('tanggal');
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->integer('durasi');
        $table->bigInteger('total_harga');
        $table->string('order_id')->nullable();
        $table->string('snap_token')->nullable();
        $table->enum('status', [
            'menunggu_pembayaran',
            'dibayar',
            'dibatalkan',
            'kadaluarsa'
        ])->default('menunggu_pembayaran');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
