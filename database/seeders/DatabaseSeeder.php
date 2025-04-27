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
            LanguageSeeder::class,
            SkillSeeder::class,
            EducationSeeder::class,
            CategorySeeder::class,
            EmployerSeeder::class,
            JobSeekerSeeder::class,
            CareerHistorySeeder::class,
            JobSeeder::class,
            JobApplySeeder::class,
            ChatRoomSeeder::class,
            ChatSeeder::class,
            ChatNotificationSeeder::class,
        ]);
    }
}
