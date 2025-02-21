<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jobs')->insert([
            [
                'employer_id'   => 1, // ensure employee with ID 1 exists
                'category_id'   => 1, // ensure category with ID 1 exists
                'job_title'     => 'Frontend Developer',
                'description'   => 'We are looking for a skilled frontend developer to build and maintain user interfaces.',
                'country_id'    => 1, // ensure country with ID 1 exists
                'salary'        => 70000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employer_id'   => 2, // ensure employee with ID 2 exists
                'category_id'   => 2, // ensure category with ID 2 exists
                'job_title'     => 'Backend Developer',
                'description'   => 'Join our backend team to build scalable APIs and server-side logic.',
                'country_id'    => 2, // ensure country with ID 2 exists
                'salary'        => 80000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employer_id'   => 1,
                'category_id'   => 3,
                'job_title'     => 'Full-Stack Engineer',
                'description'   => 'We need a full-stack engineer capable of working on both frontend and backend solutions.',
                'country_id'    => 1,
                'salary'        => 90000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employer_id'   => 2,
                'category_id'   => 1,
                'job_title'     => 'UI/UX Designer',
                'description'   => 'Creative UI/UX Designer to craft user-friendly and visually appealing interfaces.',
                'country_id'    => 2,
                'salary'        => 65000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employer_id'   => 1,
                'category_id'   => 2,
                'job_title'     => 'DevOps Engineer',
                'description'   => 'Manage our infrastructure and automate our CI/CD pipelines.',
                'country_id'    => 1,
                'salary'        => 85000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employer_id'   => 2,
                'category_id'   => 3,
                'job_title'     => 'Data Scientist',
                'description'   => 'Analyze data and build predictive models to drive business decisions.',
                'country_id'    => 2,
                'salary'        => 95000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employer_id'   => 1,
                'category_id'   => 1,
                'job_title'     => 'Product Manager',
                'description'   => 'Lead product development from concept to launch and manage cross-functional teams.',
                'country_id'    => 1,
                'salary'        => 100000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employer_id'   => 2,
                'category_id'   => 2,
                'job_title'     => 'Mobile App Developer',
                'description'   => 'Develop native and cross-platform mobile applications for our clients.',
                'country_id'    => 2,
                'salary'        => 78000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employer_id'   => 1,
                'category_id'   => 3,
                'job_title'     => 'Quality Assurance Engineer',
                'description'   => 'Ensure the quality of our products through testing and automation.',
                'country_id'    => 1,
                'salary'        => 68000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employer_id'   => 2,
                'category_id'   => 1,
                'job_title'     => 'UI Developer',
                'description'   => 'Build responsive and accessible user interfaces for our web applications.',
                'country_id'    => 2,
                'salary'        => 72000.00,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
