<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CareerHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $careerHistories = [
            [
                'job_title' => 'Software Engineer',
                'start_date' => '2019-06-01',
                'end_date' => '2022-12-31',
                'description' => 'Developed scalable web applications using Laravel and Vue.js.',
                'still_in_role' => false,
            ],
            [
                'job_title' => 'Senior Developer',
                'start_date' => '2023-01-01',
                'end_date' => null,
                'description' => 'Leading the frontend team at a fast-growing startup.',
                'still_in_role' => true,
            ],
            [
                'job_title' => 'Marketing Manager',
                'start_date' => '2018-03-15',
                'end_date' => '2021-07-20',
                'description' => 'Managed SEO strategies and digital marketing campaigns.',
                'still_in_role' => false,
            ],
            [
                'job_title' => 'Data Scientist',
                'start_date' => '2020-01-10',
                'end_date' => null,
                'description' => 'Working with AI models and large datasets to provide insights.',
                'still_in_role' => true,
            ],
            [
                'job_title' => 'DevOps Engineer',
                'start_date' => '2021-05-01',
                'end_date' => null,
                'description' => 'Implemented CI/CD pipelines and managed cloud infrastructure on AWS.',
                'still_in_role' => true,
            ],
            [
                'job_title' => 'UI/UX Designer',
                'start_date' => '2017-09-01',
                'end_date' => '2020-11-30',
                'description' => 'Designed intuitive user interfaces and conducted usability testing.',
                'still_in_role' => false,
            ],
            [
                'job_title' => 'Network Administrator',
                'start_date' => '2016-02-01',
                'end_date' => '2019-08-31',
                'description' => 'Maintained enterprise network infrastructure and resolved outages.',
                'still_in_role' => false,
            ],
            [
                'job_title' => 'Cybersecurity Analyst',
                'start_date' => '2022-04-01',
                'end_date' => null,
                'description' => 'Monitoring threats and implementing security protocols.',
                'still_in_role' => true,
            ],
            [
                'job_title' => 'Product Manager',
                'start_date' => '2020-06-01',
                'end_date' => '2023-02-28',
                'description' => 'Coordinated development teams and defined product roadmap.',
                'still_in_role' => false,
            ],
            [
                'job_title' => 'Mobile App Developer',
                'start_date' => '2018-01-01',
                'end_date' => '2021-03-01',
                'description' => 'Built Android and iOS applications using Flutter and Kotlin.',
                'still_in_role' => false,
            ],
            [
                'job_title' => 'AI Researcher',
                'start_date' => '2023-07-01',
                'end_date' => null,
                'description' => 'Conducting deep learning experiments and publishing research papers.',
                'still_in_role' => true,
            ],
            [
                'job_title' => 'IT Support Specialist',
                'start_date' => '2015-04-15',
                'end_date' => '2018-12-31',
                'description' => 'Provided technical support for hardware, software, and networks.',
                'still_in_role' => false,
            ],

        ];

        DB::table('career_histories')->insert($careerHistories);
    }
}
