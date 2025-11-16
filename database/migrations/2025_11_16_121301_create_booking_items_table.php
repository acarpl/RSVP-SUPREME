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
    Schema::create('booking_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('booking_id');
        $table->unsignedBigInteger('item_id'); // product or lapangan id
        $table->string('item_type');           // 'product' or 'lapangan'
        $table->integer('qty')->default(1);
        $table->decimal('price', 12, 2);
        $table->text('meta')->nullable(); // e.g. jam main
        $table->timestamps();

        $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
