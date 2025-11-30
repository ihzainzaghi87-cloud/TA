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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_address_id')->nullable()->constrained('user_addresses')->onDelete('set null');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 15, 2)->default(0); // Total uang
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0); // Total uang
            $table->integer('total_points_used')->default(0); // Total poin digunakan
            $table->integer('points_earned')->default(0); // Poin yang didapat
            $table->string('status')->default('Pending');
            $table->string('snap_token')->nullable();
            $table->string('payment_status')->default('Pending');
            $table->text('notes')->nullable();
            // Simpan snapshot alamat saat order dibuat (untuk history)
            $table->string('shipping_recipient_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('courier')->nullable(); // jne, pos, tiki, etc
            $table->string('service')->nullable(); // REG, YES, OKE, etc
            $table->integer('weight')->default(0); // dalam gram
            $table->unsignedInteger('origin_city_id')->nullable(); // ID kota asal
            $table->unsignedInteger('destination_city_id')->nullable(); // ID kota tujuan
            // Tracking information
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
