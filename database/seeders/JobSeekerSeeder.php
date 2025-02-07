<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class JobSeekerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Ensure users and countries exist
         $users = DB::table('users')->pluck('id')->toArray();
         $countries = DB::table('countries')->pluck('id')->toArray();

         if (empty($users)) {
             $this->command->warn('No users found. Please seed the users table first.');
             return;
         }

         $jobSeekers = [
             [
                 'user_id' => $users[0] ?? 1,
                 'language_id' => '1,2', // Assuming multiple languages are comma-separated
                 'skill_id' => '3,4,5',
                 'country_id' => $countries[0] ?? null,
                 'profile_img' => 'https://placehold.co/150x150',
                 'social_url' => 'https://linkedin.com/in/jobseeker1',
                 'personal_summary' => 'Experienced software engineer with a passion for innovation.',
                 'career_history_id' => '1,2',
                 'education_id' => '1,3',
                 'resume_url' => 'https://example.com/resume1.pdf',
             ],
             [
                 'user_id' => $users[1] ?? 2,
                 'language_id' => '2,3',
                 'skill_id' => '1,6',
                 'country_id' => $countries[1] ?? null,
                 'profile_img' => 'https://placehold.co/150x150',
                 'social_url' => 'https://linkedin.com/in/jobseeker2',
                 'personal_summary' => 'Marketing expert with a focus on digital strategies.',
                 'career_history_id' => '3,4',
                 'education_id' => '2,4',
                 'resume_url' => 'https://example.com/resume2.pdf',
             ],
             [
                 'user_id' => $users[2] ?? 3,
                 'language_id' => '1,4',
                 'skill_id' => '2,5,7',
                 'country_id' => $countries[2] ?? null,
                 'profile_img' => 'https://placehold.co/150x150',
                 'social_url' => 'https://linkedin.com/in/jobseeker3',
                 'personal_summary' => 'Data analyst with expertise in AI-driven insights.',
                 'career_history_id' => '5',
                 'education_id' => '3,5',
                 'resume_url' => 'https://example.com/resume3.pdf',
             ]
         ];

         DB::table('jobseekers')->insert($jobSeekers);
    }
}
