<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_alamat_pengiriman_to_bookings_table.php
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->text('alamat_pengiriman')->nullable();
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('alamat_pengiriman');
        });
    }
};
