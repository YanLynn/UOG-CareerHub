<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'companyName' => 'Tech Solutions',
            'description' => 'Innovative tech company.',
            'industry' => 'IT',
            'website' => 'https://techsolutions.com',
            'location' => 'New York, USA'
        ]);

        Company::create([
            'companyName' => 'Creative Agency',
            'description' => 'Marketing and creative solutions.',
            'industry' => 'Marketing',
            'website' => 'https://creativeagency.com',
            'location' => 'London, UK'
        ]);
    }
}
