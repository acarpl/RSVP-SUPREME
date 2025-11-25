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
    Schema::table('bookings', function (Blueprint $table) {
        $table->string('jenis_pesanan')->after('user_id')->nullable();
        // opsional: rename `lapangan_id` jadi nullable kalau belum
        $table->unsignedBigInteger('lapangan_id')->nullable()->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
