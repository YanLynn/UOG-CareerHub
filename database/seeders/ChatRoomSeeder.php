<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ChatRoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chat_rooms')->insert([
            [
                'employer_id' => 1,
                'jobseeker_id' => 1,
                'job_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'employer_id' => 2,
                'jobseeker_id' => 2,
                'job_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
