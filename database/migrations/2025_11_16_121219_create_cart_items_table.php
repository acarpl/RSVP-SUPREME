<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('cart_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('cart_id');
        $table->unsignedBigInteger('item_id');      // id dari products atau lapangans
        $table->string('item_type');                // 'product' atau 'lapangan'
        $table->integer('qty')->default(1);
        $table->decimal('price', 12, 2);
        $table->timestamps();

        $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
