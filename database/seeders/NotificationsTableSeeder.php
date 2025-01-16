<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::create([
            'user_id' => 3,
            'content' => 'Your application for the Frontend Developer role has been submitted.',
            'isRead' => false
        ]);

        Notification::create([
            'user_id' => 2,
            'content' => 'New application received for the Frontend Developer role.',
            'isRead' => false
        ]);
    }
}
