<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ChatNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chat_notifications')->insert([
            [
                'chat_room_id' => 1,
                'recipient_user_id' => 1,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => 2,
                'recipient_user_id' => 2,
                'is_read' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
