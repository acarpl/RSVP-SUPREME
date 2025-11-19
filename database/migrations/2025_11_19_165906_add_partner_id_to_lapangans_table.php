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
    Schema::table('lapangans', function (Blueprint $table) {
        $table->unsignedBigInteger('partner_id')->nullable()->after('id');
        $table->foreign('partner_id')->references('id')->on('users')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('lapangans', function (Blueprint $table) {
        $table->dropForeign(['partner_id']);
        $table->dropColumn('partner_id');
    });
}
};
