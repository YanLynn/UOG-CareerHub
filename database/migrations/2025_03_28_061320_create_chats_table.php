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
    Schema::create('chats', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('chat_room_id');
        $table->unsignedBigInteger('sender_id');
        $table->enum('sender_type', ['employer', 'jobseeker']);
        $table->text('message');
        $table->timestamps();

        $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
