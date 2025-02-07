<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educations = [
            [
                'qualification' => 'Bachelor of Science in Computer Science',
                'institution' => 'University of California, Berkeley',
                'date' => '2019-06-15',
                'qualification_status' => true,
                'description' => 'Studied algorithms, data structures, and software engineering.',
            ],
            [
                'qualification' => 'Master of Business Administration (MBA)',
                'institution' => 'Harvard Business School',
                'date' => '2021-05-30',
                'qualification_status' => true,
                'description' => 'Specialized in marketing and financial management.',
            ],
            [
                'qualification' => 'Diploma in Graphic Design',
                'institution' => 'New York School of Design',
                'date' => '2020-12-10',
                'qualification_status' => true,
                'description' => 'Focused on branding, UI/UX, and typography.',
            ],
            [
                'qualification' => 'Certified Data Analyst',
                'institution' => 'Google Data Analytics Certification',
                'date' => null, // No specific graduation date
                'qualification_status' => false,
                'description' => 'Ongoing professional certification for data analysis skills.',
            ],
            [
                'qualification' => 'High School Diploma',
                'institution' => 'Springfield High School',
                'date' => '2016-06-01',
                'qualification_status' => true,
                'description' => 'Completed secondary education with a focus on science and mathematics.',
            ]
        ];

        DB::table('educations')->insert($educations);
    }
}
