<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('order_number')->unique(); // SPORTY-ORD-20251124-001
    $table->string('alamat');
    $table->string('catatan')->nullable();
    $table->string('status')->default('menunggu_pembayaran'); // menunggu_pembayaran, dibayar, dikirim, selesai, dibatalkan
    $table->string('payment_method')->default('midtrans');
    $table->string('payment_status')->default('pending'); // pending, settlement, cancel, expire
    $table->bigInteger('total');
    $table->string('order_id_midtrans')->nullable(); // untuk webhook
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
