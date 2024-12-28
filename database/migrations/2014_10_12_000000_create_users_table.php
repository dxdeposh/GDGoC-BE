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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('alamat');
            $table->string('no_telepon');
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->enum('role', ['buyer', 'seller', 'admin', 'moderator']);
            $table->string('foto_profil')->nullable();
            $table->enum('status_verifikasi', ['verified', 'unverified', 'pending'])->default('pending');
            $table->float('rating')->default(0);
            $table->integer('total_transaksi')->default(0);
            $table->float('lokasi_geolokasi_latitude')->nullable();
            $table->float('lokasi_geolokasi_longitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
