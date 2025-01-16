<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Application;
class ApplicationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Application::create([
            'user_id' => 3,
            'job_id' => 1,
            'status' => 'Pending',
            'resume' => 'resumes/jobseeker1_resume.pdf'
        ]);

        Application::create([
            'user_id' => 3,
            'job_id' => 2,
            'status' => 'Pending',
            'resume' => 'resumes/jobseeker1_resume.pdf'
        ]);
    }
}
