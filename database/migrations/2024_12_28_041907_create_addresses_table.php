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
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('address_id')->primary();
            $table->uuid('user_id');
            $table->string('alamat');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos');
            $table->string('negara');
            $table->float('lokasi_geolokasi_latitude')->nullable();
            $table->float('lokasi_geolokasi_longitude')->nullable();
            $table->enum('tipe_alamat', ['utama', 'tambahan'])->default('utama');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
