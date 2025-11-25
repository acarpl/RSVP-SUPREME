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
    Schema::create('booking_confirmations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('partner_id')->constrained('users'); // partner yang konfirmasi
        $table->enum('type', ['booking', 'order']);
        $table->string('status')->default('menunggu_konfirmasi'); // menunggu_konfirmasi, dikonfirmasi, ditolak
        $table->text('catatan')->nullable();
        $table->timestamps();
    });

    // Tambah kolom ke bookings & orders
    Schema::table('bookings', function (Blueprint $table) {
        $table->foreignId('confirmed_by_partner_id')->nullable()->constrained('users');
        $table->string('partner_status')->default('menunggu_konfirmasi'); // menunggu_konfirmasi, dikonfirmasi, ditolak
    });

    Schema::table('orders', function (Blueprint $table) {
        $table->foreignId('confirmed_by_partner_id')->nullable()->constrained('users');
        $table->string('partner_status')->default('menunggu_konfirmasi');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_confirmations');
    }
};
