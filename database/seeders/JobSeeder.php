<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $jobTypes = ['full-time', 'part-time', 'contract', 'internship', 'freelance'];
        $experienceLevels = ['Entry-level', 'Mid-level', 'Senior'];
        $employmentStatuses = ['open', 'closed', 'pending'];
        $visibilities = ['public', 'private', 'featured'];

        for ($i = 1; $i <= 20; $i++) {
            DB::table('jobs')->insert([
                'employer_id' => rand(1, 5), // Assuming you have 5 employers
                'category_id' => rand(1, 5), // Assuming you have 5 categories
                'job_title' => $faker->jobTitle,
                'description' => $faker->paragraph(3),
                'country_id' => rand(1, 5), // Assuming you have 5 countries
                'salary' => $faker->randomFloat(2, 50000, 150000),
                'job_type' => $faker->randomElement($jobTypes),
                'job_location' => $faker->city . ', ' . $faker->country,
                'experience_level' => $faker->randomElement($experienceLevels),
                'skills' => implode(', ', $faker->words(5)),
                'employment_status' => $faker->randomElement($employmentStatuses),
                'application_deadline' => Carbon::now()->addDays(rand(10, 90)),
                'benefits' => implode(', ', $faker->words(3)),
                'visibility' => $faker->randomElement($visibilities),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
