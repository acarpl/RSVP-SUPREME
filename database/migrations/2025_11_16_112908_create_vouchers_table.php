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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id'); // pembuat voucher
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']);
            $table->integer('value');                 // % atau nominal
            $table->integer('min_amount')->default(0);
            $table->integer('max_discount')->nullable();
            $table->integer('quota')->default(0);     // ➜ penting, wajib ada!
            $table->date('expires_at')->nullable();
            $table->timestamps();

            // optional FK (aman jika tabel partners/users sudah ada)
            $table->foreign('partner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
