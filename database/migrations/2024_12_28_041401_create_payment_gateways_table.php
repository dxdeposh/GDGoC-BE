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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->uuid('payment_gateway_id')->primary();
            $table->string('nama_gateway');
            $table->string('api_key');
            $table->string('api_secret');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
