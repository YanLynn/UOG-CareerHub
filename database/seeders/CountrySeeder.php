<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class CountrySeeder extends Seeder
{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('countries')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $json = File::get(database_path('data/currencies-with-flags.json'));
        $countries = json_decode($json, true);

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'code'        => $country['code'] ?? null,
                'name'        => $country['name'] ?? null,
                'country'     => $country['country'] ?? null,
                'countryCode' => $country['countryCode'] ?? null,
                'flag'        => $country['flag'] ?? null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
