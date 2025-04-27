<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;
class JobApplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Fetch existing job IDs to avoid foreign key issues
        $jobIds = DB::table('jobs')->pluck('id')->toArray();
        $jobseekerIds = DB::table('jobseekers')->pluck('id')->toArray();

        if (empty($jobIds) || empty($jobseekerIds)) {
            return; // Stop if no jobs or jobseekers exist
        }

        for ($i = 1; $i <= 20; $i++) {
            DB::table('job_applies')->insert([
                'job_id' => $faker->randomElement($jobIds), // Ensure job_id exists
                'jobseeker_id' => $faker->randomElement($jobseekerIds),
                'job_apply_date' => Carbon::now()->subDays(rand(1, 30)),
                'status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                'type' => $faker->randomElement(['saved', 'unsaved']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
