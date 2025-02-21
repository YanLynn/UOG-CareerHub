<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class JobApplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('job_applies')->insert([
            [
                'job_id' => 1,
                'jobseeker_id' => 1,
                'status' => 'pending',
                'job_apply_date' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'job_id' => 2,
                'jobseeker_id' => 1,
                'status' => 'approved',
                'job_apply_date' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'job_id' => 3,
                'jobseeker_id' => 2,
                'status' => 'saved',
                'job_apply_date' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'job_id' => 4,
                'jobseeker_id' => 3,
                'status' => 'pending',
                'job_apply_date' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'job_id' => 5,
                'jobseeker_id' => 2,
                'status' => 'approved',
                'job_apply_date' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
