<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('name')->after('partner_id'); // 🔥 WAJIB
            $table->string('discount_type')->nullable()->after('type');
            $table->integer('discount_value')->nullable()->after('value');
            $table->date('valid_from')->nullable()->after('expires_at');
            $table->date('valid_until')->nullable()->after('valid_from');
            $table->string('image')->nullable()->after('code'); // 🔥 WAJIB
            $table->boolean('is_active')->default(true)->after('image');
        });
    }

    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'discount_type',
                'discount_value',
                'valid_from',
                'valid_until',
                'image',
                'is_active'
            ]);
        });
    }
};