<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class JobSeeder extends Seeder
{

    public function run()
{
    $faker = Faker::create();

    $jobTypes = ['full-time', 'part-time', 'contract', 'internship', 'freelance'];
    $experienceLevels = ['Entry-level', 'Mid-level', 'Senior'];
    $employmentStatuses = ['open', 'closed', 'pending'];
    $visibilities = ['public', 'private'];

    // 🔁 Fetch existing IDs from DB
    $employerIds = DB::table('employers')->pluck('id')->toArray();
    $categoryIds = DB::table('categories')->pluck('id')->toArray();
    $countryIds = DB::table('countries')->pluck('id')->toArray();

    for ($i = 1; $i <= 2000; $i++) {
        DB::table('jobs')->insert([
            'employer_id' => $faker->randomElement($employerIds),
            'category_id' => $faker->randomElement($categoryIds),
            'job_title' => $faker->jobTitle,
            'description' => $faker->paragraph(3),
            'country_id' => $faker->randomElement($countryIds),
            'salary' => $faker->randomFloat(2, 50000, 150000),
            'job_type' => $faker->randomElement($jobTypes),
            'job_location' => $faker->city . ', ' . $faker->country,
            'experience_level' => $faker->randomElement($experienceLevels),
            'requirements' => implode(', ', $faker->words(5)),
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
