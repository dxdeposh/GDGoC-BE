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
            $table->uuid('order_id')->primary();
            $table->uuid('buyer_id');
            $table->uuid('seller_id');
            $table->uuid('product_id');
            $table->integer('jumlah');
            $table->float('total_harga');
            $table->enum('status_order', ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan', 'retur'])->default('pending');
            $table->timestamp('tanggal_pesan')->useCurrent();
            $table->string('alamat_pengiriman');
            $table->string('kode_resi')->nullable();
            $table->string('metode_pengiriman')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('buyer_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('seller_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
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
