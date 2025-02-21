<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class EmployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();


        foreach ($userIds as $userId) {
            DB::table('employers')->insert([
                'user_id'        => $userId,
                'company_name'   => 'Company ' . $userId,
                'company_website'=> 'https://example.com/company-' . $userId,
                'company_image'  => null,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);
        }

    }
}
