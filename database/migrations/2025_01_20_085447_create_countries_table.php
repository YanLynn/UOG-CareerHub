<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();          // Currency code (e.g., ZMW)
            $table->string('name');                    // Currency name (e.g., Zambian Kwacha)
            $table->string('country');                 // Country name (e.g., Zambia)
            $table->string('countryCode', 3);          // ISO Alpha-2 code (e.g., ZM)
            $table->longText('flag')->nullable();      // base64 image
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('countries');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
