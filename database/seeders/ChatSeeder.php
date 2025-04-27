<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chats')->insert([
            [
                'chat_room_id' => 1,
                'sender_id' => 1,
                'sender_type' => 'employer',
                'message' => 'Hello, are you interested in the job?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => 1,
                'sender_id' => 1,
                'sender_type' => 'jobseeker',
                'message' => 'Yes, I would like to know more.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
