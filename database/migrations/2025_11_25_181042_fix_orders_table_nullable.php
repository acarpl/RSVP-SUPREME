<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // ✅ Jadikan nullable
            $table->string('alamat')->nullable()->change();
            $table->bigInteger('total')->nullable(false)->default(0)->change(); // atau hapus default jika wajib diisi via controller
            
            // ✅ Tambah kolom yang dibutuhkan untuk sewa_alat & beli_produk
            $table->string('jenis_pesanan')->default('beli_produk')->after('order_number');
            $table->date('tanggal')->nullable()->after('alamat');
            $table->time('jam_mulai')->nullable()->after('tanggal');
            $table->integer('durasi')->nullable()->after('jam_mulai');
            
            // ✅ Ganti 'alamat' → 'alamat_pengiriman' agar konsisten
            $table->renameColumn('alamat', 'alamat_pengiriman');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('alamat_pengiriman')->nullable(false)->change();
            $table->bigInteger('total')->nullable(false)->change();
            $table->dropColumn(['jenis_pesanan', 'tanggal', 'jam_mulai', 'durasi']);
            $table->renameColumn('alamat_pengiriman', 'alamat');
        });
    }
};