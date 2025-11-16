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
        $table->unsignedBigInteger('user_id');
        $table->decimal('subtotal', 12, 2);
        $table->decimal('discount', 12, 2)->default(0);
        $table->decimal('total', 12, 2);
        $table->unsignedBigInteger('voucher_id')->nullable();
        $table->string('status')->default('pending'); // pending, paid, cancelled
        $table->text('meta')->nullable(); // json for additional info (jadwal, phone, etc)
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('voucher_id')->references('id')->on('vouchers')->nullOnDelete();
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
