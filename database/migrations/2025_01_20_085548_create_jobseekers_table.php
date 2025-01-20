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
        Schema::create('jobseekers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('language_id')->nullable();
            $table->string('skill_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('profile_img')->nullable();
            $table->string('social_url')->nullable();
            $table->text('personal_summary')->nullable();
            $table->string('career_history_id')->nullable();
            $table->string('education_id')->nullable();
            $table->string('resume_url')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobseekers');
    }
};
