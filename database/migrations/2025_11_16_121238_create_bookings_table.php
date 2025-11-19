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
        $table->integer('durasi'); // dalam jam
        $table->decimal('total_harga', 12, 2);
        $table->enum('status', ['menunggu', 'dikonfirmasi', 'selesai', 'dibatalkan'])->default('menunggu');
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
