<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'English'],
            ['name' => 'Spanish'],
            ['name' => 'French'],
            ['name' => 'Mandarin Chinese'],
            ['name' => 'Burmese'],
            ['name' => 'Arabic'],
            ['name' => 'Bengali'],
            ['name' => 'Portuguese'],
            ['name' => 'Russian'],
            ['name' => 'Japanese'],
            ['name' => 'German'],
            ['name' => 'Korean'],
            ['name' => 'Italian'],
            ['name' => 'Turkish'],
            ['name' => 'Dutch'],
            ['name' => 'Swedish'],
            ['name' => 'Greek'],
            ['name' => 'Polish'],
            ['name' => 'Hebrew'],
            ['name' => 'Thai'],
        ];

        DB::table('languages')->insert($languages);
    }
}
