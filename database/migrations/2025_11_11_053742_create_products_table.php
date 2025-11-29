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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('weight')->default(0); // berat dalam gram
            $table->decimal('price', 10, 2);
            $table->integer('point_price')->nullable(); // Harga dalam poin
            $table->boolean('is_active')->default(true);
            $table->boolean('is_reward')->default(false); // Produk Reward
            $table->timestamps();

            $table->index(['is_reward', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
