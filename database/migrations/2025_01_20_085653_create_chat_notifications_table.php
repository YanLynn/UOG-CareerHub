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
        Schema::create('chat_notifications', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('chat_id');
        $table->unsignedBigInteger('recipient_user_id');
        $table->boolean('is_read')->default(false);
        $table->timestamps();

        $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
        $table->foreign('recipient_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_notifications');
    }
};
