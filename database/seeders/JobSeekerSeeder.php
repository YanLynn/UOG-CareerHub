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
             ],
             [
                'user_id' => $users[3] ?? 4,
                'language_id' => '1,3',
                'skill_id' => '8,9,10',
                'country_id' => $countries[3] ?? null,
                'profile_img' => 'https://placehold.co/150x150',
                'social_url' => 'https://linkedin.com/in/jobseeker4',
                'personal_summary' => 'Frontend developer skilled in modern JavaScript frameworks and responsive UI.',
                'career_history_id' => '6,7',
                'education_id' => '4,5',
                'resume_url' => 'https://example.com/resume4.pdf',
            ],
            [
                'user_id' => $users[4] ?? 5,
                'language_id' => '2',
                'skill_id' => '11,12,13',
                'country_id' => $countries[4] ?? null,
                'profile_img' => 'https://placehold.co/150x150',
                'social_url' => 'https://linkedin.com/in/jobseeker5',
                'personal_summary' => 'UI/UX designer passionate about human-centered design and mobile interfaces.',
                'career_history_id' => '8',
                'education_id' => '1,2',
                'resume_url' => 'https://example.com/resume5.pdf',
            ],
            [
                'user_id' => $users[5] ?? 6,
                'language_id' => '1,2,3',
                'skill_id' => '14,15,16',
                'country_id' => $countries[5] ?? null,
                'profile_img' => 'https://placehold.co/150x150',
                'social_url' => 'https://linkedin.com/in/jobseeker6',
                'personal_summary' => 'AI researcher and data scientist focused on machine learning algorithms.',
                'career_history_id' => '9',
                'education_id' => '2,5',
                'resume_url' => 'https://example.com/resume6.pdf',
            ],
            [
                'user_id' => $users[6] ?? 7,
                'language_id' => '1,3',
                'skill_id' => '17,18',
                'country_id' => $countries[6] ?? null,
                'profile_img' => 'https://placehold.co/150x150',
                'social_url' => 'https://linkedin.com/in/jobseeker7',
                'personal_summary' => 'Cybersecurity analyst with a focus on threat detection and response.',
                'career_history_id' => '10',
                'education_id' => '3,4',
                'resume_url' => 'https://example.com/resume7.pdf',
            ],
            [
                'user_id' => $users[7] ?? 8,
                'language_id' => '2,4',
                'skill_id' => '6,8,14',
                'country_id' => $countries[7] ?? null,
                'profile_img' => 'https://placehold.co/150x150',
                'social_url' => 'https://linkedin.com/in/jobseeker8',
                'personal_summary' => 'Full-stack developer experienced in Laravel, Vue, and RESTful APIs.',
                'career_history_id' => '11',
                'education_id' => '1,5',
                'resume_url' => 'https://example.com/resume8.pdf',
            ],
            [
                'user_id' => $users[8] ?? 9,
                'language_id' => '1',
                'skill_id' => '5,7,11',
                'country_id' => $countries[8] ?? null,
                'profile_img' => 'https://placehold.co/150x150',
                'social_url' => 'https://linkedin.com/in/jobseeker9',
                'personal_summary' => 'Backend engineer focused on Node.js, SQL, and scalable architectures.',
                'career_history_id' => '12',
                'education_id' => '2,4',
                'resume_url' => 'https://example.com/resume9.pdf',
            ],
            [
                'user_id' => $users[9] ?? 10,
                'language_id' => '3,4',
                'skill_id' => '12,15,20',
                'country_id' => $countries[9] ?? null,
                'profile_img' => 'https://placehold.co/150x150',
                'social_url' => 'https://linkedin.com/in/jobseeker10',
                'personal_summary' => 'Cloud engineer with experience in AWS infrastructure and DevOps automation.',
                'career_history_id' => '13',
                'education_id' => '3,5',
                'resume_url' => 'https://example.com/resume10.pdf',
            ],
         ];

         DB::table('jobseekers')->insert($jobSeekers);
    }
}
