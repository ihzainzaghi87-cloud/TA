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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Label alamat
            $table->string('label')->nullable(); // "Rumah", "Kantor", "Apartemen"
            // Informasi penerima
            $table->string('recipient_name');
            $table->string('phone', 15);
            // Alamat lengkap
            $table->text('address'); // Jalan, nomor rumah, RT/RW
            // Integrasi dengan RajaOngkir
            $table->unsignedInteger('province_id'); // ID provinsi dari RajaOngkir
            $table->string('province_name');
            $table->unsignedInteger('city_id'); // ID kota dari RajaOngkir
            $table->string('city_name');
            $table->string('city_type')->nullable(); // Kota/Kabupaten
            // Detail tambahan
            $table->string('postal_code', 10)->nullable();
            $table->text('note')->nullable(); // Catatan/patokan
            // Flag
            $table->boolean('is_primary')->default(false); // Alamat utama
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_primary']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
