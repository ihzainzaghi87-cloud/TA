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
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['earned', 'redeemed']);
            $table->integer('points'); // Positif untuk tambah, negatif untuk kurang
            $table->integer('balance_after');
            $table->string('description');
            $table->morphs('transactionable'); // Polymorphic: Order, OrderItem, dll
            // $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->timestamps();
            
            $table->index(['user_id', 'type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
