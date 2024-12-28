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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('review_id')->primary();
            $table->uuid('product_id');
            $table->uuid('user_id');
            $table->integer('rating');
            $table->text('komentar')->nullable();
            $table->timestamp('tanggal_review')->useCurrent();
            $table->enum('status_review', ['approved', 'pending', 'rejected'])->default('pending');
            $table->boolean('replied')->default(false);
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
        Schema::dropIfExists('reviews');
    }
};
