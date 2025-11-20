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
        $table->foreignId('lapangan_id')->nullable()->after('id');
        $table->foreign('lapangan_id')->references('id')->on('lapangans')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropForeign(['lapangan_id']);
        $table->dropColumn('lapangan_id');
    });
}
};
