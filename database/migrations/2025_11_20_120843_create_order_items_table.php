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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('variation_id')->constrained('variations');
            $table->string('product_name');
            $table->string('variant_details'); // "Warna: Merah, Ukuran: L"
            $table->integer('quantity');
            $table->decimal('price', 15, 2)->default(0); // Harga uang
            $table->integer('point_price')->default(0); // Harga poin
            $table->decimal('subtotal', 15, 2)->default(0); // Subtotal uang
            $table->integer('point_subtotal')->default(0); // Subtotal poin
            $table->timestamps();

            $table->index(['order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
