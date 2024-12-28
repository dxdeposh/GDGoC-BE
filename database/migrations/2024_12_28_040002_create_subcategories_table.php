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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->uuid('sub_category_id')->primary();
            $table->uuid('category_id');
            $table->string('nama_sub_kategori');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
