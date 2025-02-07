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
            ]
        ];

        DB::table('career_histories')->insert($careerHistories);
    }
}
