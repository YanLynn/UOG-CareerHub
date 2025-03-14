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
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('company_name');
            $table->string('company_website')->nullable();
            $table->string('company_image')->nullable();
            $table->string('industry')->nullable();
            $table->enum('company_size', ['Small', 'Medium', 'Large'])->nullable();
            $table->text('company_description')->nullable();
            $table->year('founded_year')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->boolean('verified')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
