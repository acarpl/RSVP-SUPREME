<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lapangan_id');

            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->timestamps();

            // Foreign keys (opsional)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lapangan_id')->references('id')->on('lapangans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
