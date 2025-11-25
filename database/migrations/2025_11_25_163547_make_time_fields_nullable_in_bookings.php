<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->change();
            $table->time('jam_selesai')->nullable()->change();
            $table->integer('durasi')->nullable()->change();
            // Opsional: pastikan lapangan_id juga nullable
            $table->unsignedBigInteger('lapangan_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable(false)->change();
            $table->time('jam_selesai')->nullable(false)->change();
            $table->integer('durasi')->nullable(false)->change();
            $table->unsignedBigInteger('lapangan_id')->nullable(false)->change();
        });
    }
};