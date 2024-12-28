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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('transaction_id')->primary();
            $table->uuid('order_id');
            $table->string('metode_pembayaran');
            $table->enum('status_transaksi', ['pending', 'sukses', 'gagal', 'refund'])->default('pending');
            $table->timestamp('tanggal_transaksi')->useCurrent();
            $table->json('detail_pembayaran')->nullable();
            $table->float('amount');
            $table->string('currency')->default('IDR');
            $table->uuid('payment_gateway_id')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('payment_gateway_id')->references('payment_gateway_id')->on('payment_gateways')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
