<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'United States', 'postal_code' => 'US'],
            ['name' => 'Canada', 'postal_code' => 'CA'],
            ['name' => 'United Kingdom', 'postal_code' => 'GB'],
            ['name' => 'Australia', 'postal_code' => 'AU'],
            ['name' => 'Germany', 'postal_code' => 'DE'],
            ['name' => 'France', 'postal_code' => 'FR'],
            ['name' => 'Italy', 'postal_code' => 'IT'],
            ['name' => 'Spain', 'postal_code' => 'ES'],
            ['name' => 'Japan', 'postal_code' => 'JP'],
            ['name' => 'China', 'postal_code' => 'CN'],
            ['name' => 'India', 'postal_code' => 'IN'],
            ['name' => 'Brazil', 'postal_code' => 'BR'],
            ['name' => 'South Africa', 'postal_code' => 'ZA'],
            ['name' => 'Mexico', 'postal_code' => 'MX'],
            ['name' => 'Netherlands', 'postal_code' => 'NL'],
            ['name' => 'Switzerland', 'postal_code' => 'CH'],
            ['name' => 'Russia', 'postal_code' => 'RU'],
            ['name' => 'South Korea', 'postal_code' => 'KR'],
            ['name' => 'Sweden', 'postal_code' => 'SE'],
            ['name' => 'Norway', 'postal_code' => 'NO']
        ];

        DB::table('countries')->insert($countries);
    }
}
