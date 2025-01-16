<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chat;
class ChatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chat::create([
            'sender_id' => 3,
            'receiver_id' => 2,
            'message' => 'Hi, I am interested in the Frontend Developer role.'
        ]);

        Chat::create([
            'sender_id' => 2,
            'receiver_id' => 3,
            'message' => 'Thank you for your interest. We will review your application.'
        ]);
    }
}
