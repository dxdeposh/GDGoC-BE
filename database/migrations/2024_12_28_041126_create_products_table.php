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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('product_id')->primary();
            $table->uuid('user_id');
            $table->string('nama_produk');
            $table->text('deskripsi');
            $table->float('harga');
            $table->uuid('category_id');
            $table->uuid('sub_category_id');
            $table->integer('stok');
            $table->timestamp('tanggal_upload')->useCurrent();
            $table->enum('status', ['tersedia', 'terjual', 'diarsipkan', 'ditunda'])->default('tersedia');
            $table->json('gambar')->nullable();
            $table->float('lokasi_geolokasi_latitude')->nullable();
            $table->float('lokasi_geolokasi_longitude')->nullable();
            $table->enum('kondisi', ['baru', 'hampir baru', 'bekas']);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('warna')->nullable();
            $table->boolean('garansi')->default(false);
            $table->string('estimasi_pengiriman')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('sub_category_id')->on('subcategories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
