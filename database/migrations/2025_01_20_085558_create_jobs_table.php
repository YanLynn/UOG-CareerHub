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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employer_id');
            $table->unsignedBigInteger('category_id');
            $table->string('job_title');
            $table->text('description');
            $table->unsignedBigInteger('country_id');
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('job_type')->default('full-time'); // Full-time, Part-time, Contract, etc.
            $table->string('job_location')->nullable(); // Location or 'Remote'
            $table->string('experience_level')->nullable(); // Entry, Mid, Senior
            $table->text('requirements')->nullable(); // Required skills (comma-separated)
            $table->enum('employment_status', ['open', 'closed', 'pending'])->default('open'); // Job status
            $table->date('application_deadline')->nullable(); // Application closing date
            $table->text('benefits')->nullable(); // Benefits offered
            $table->enum('visibility', ['public', 'private', 'featured'])->default('public'); // Job visibility

            $table->timestamps();

            $table->foreign('employer_id')->references('id')->on('employers')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
