<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Job;
class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Job::create([
            'company_id' => 1,
            'title' => 'Frontend Developer',
            'description' => 'Develop responsive web applications.',
            'location' => 'Remote',
            'employmentType' => 'FullTime',
            'salary' => 80000,
            'status' => 'Open'
        ]);

        Job::create([
            'company_id' => 2,
            'title' => 'Marketing Specialist',
            'description' => 'Manage marketing campaigns.',
            'location' => 'London',
            'employmentType' => 'PartTime',
            'salary' => 40000,
            'status' => 'Open'
        ]);
    }
}
