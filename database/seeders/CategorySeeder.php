<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('categories')->insert([
            [
                'name' => 'Software Engineering',
                'description' => 'Jobs in software development, system architecture, and software lifecycle management.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Data Science',
                'description' => 'Jobs in data modeling, big data processing, and data-driven decision making.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Artificial Intelligence',
                'description' => 'Jobs related to AI development, machine learning, and automation.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'DevOps',
                'description' => 'Jobs focused on continuous integration, automation, and infrastructure as code.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'IT Support',
                'description' => 'Jobs in technical support, troubleshooting, and end-user system assistance.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Information Security',
                'description' => 'Jobs in cybersecurity, ethical hacking, and protecting digital assets.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cloud Services',
                'description' => 'Jobs working with cloud platforms like AWS, Azure, and Google Cloud.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Jobs in iOS, Android, and cross-platform mobile app development.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'QA & Testing',
                'description' => 'Jobs in software quality assurance, test automation, and debugging.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Game Development',
                'description' => 'Jobs in building, designing, and testing video games and interactive experiences.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Technology',
                'description' => 'Jobs related to software development, IT, and tech innovations.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Finance',
                'description' => 'Jobs related to banking, accounting, and financial services.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Healthcare',
                'description' => 'Jobs in hospitals, clinics, and the medical field.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Education',
                'description' => 'Jobs in teaching, administration, and educational services.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Marketing',
                'description' => 'Jobs related to advertising, digital marketing, and communications.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sales',
                'description' => 'Jobs in sales, business development, and customer acquisition.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Customer Service',
                'description' => 'Jobs related to client support and customer satisfaction.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Human Resources',
                'description' => 'Jobs related to recruiting, employee relations, and HR management.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Legal',
                'description' => 'Jobs in law firms, legal advisory, and compliance.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Engineering',
                'description' => 'Jobs in mechanical, electrical, civil, and other engineering fields.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Research & Development',
                'description' => 'Jobs focused on innovation, product development, and scientific research.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Administration',
                'description' => 'Jobs related to office administration and support services.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Operations',
                'description' => 'Jobs focusing on business operations, process optimization, and efficiency.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Supply Chain',
                'description' => 'Jobs in procurement, logistics, inventory, and supply chain management.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Hospitality',
                'description' => 'Jobs in hotels, restaurants, tourism, and customer service in the hospitality sector.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Retail',
                'description' => 'Jobs in retail management, customer sales, and merchandising.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Consulting',
                'description' => 'Jobs in management consulting, business advisory, and strategy planning.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Real Estate',
                'description' => 'Jobs in property management, real estate sales, leasing, and development.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Logistics',
                'description' => 'Jobs in transportation, shipping, and distribution management.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Manufacturing',
                'description' => 'Jobs in production, quality control, and industrial operations.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
