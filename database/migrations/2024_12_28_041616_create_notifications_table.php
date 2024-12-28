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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('notification_id')->primary();
            $table->uuid('user_id');
            $table->enum('type', ['order_update', 'new_message', 'system_alert', 'promotion', 'review']);
            $table->string('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('tanggal')->useCurrent();
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
        Schema::dropIfExists('notifications');
    }
};
