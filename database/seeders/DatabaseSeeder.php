<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UsersTableSeeder::class,
            CountrySeeder::class,
            SkillSeeder::class,
            LanguageSeeder::class,
            CareerHistorySeeder::class,
            EducationSeeder::class,
            JobSeekerSeeder::class
        ]);
    }
}
