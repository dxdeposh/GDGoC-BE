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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->uuid('log_id')->primary();
            $table->uuid('product_id');
            $table->enum('action', ['add', 'update', 'delete', 'sell', 'return']);
            $table->uuid('user_id');
            $table->json('detail')->nullable();
            $table->timestamp('tanggal')->useCurrent();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
