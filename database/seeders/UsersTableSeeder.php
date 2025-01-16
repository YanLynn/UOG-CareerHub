<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        User::create([
            'name' => 'employer',
            'email' => 'employer1@example.com',
            'password' => Hash::make('password'),
            'role' => 'Employer',
        ]);

        User::create([
            'name' => 'jobseeker',
            'email' => 'jobseeker1@example.com',
            'password' => Hash::make('password'),
            'role' => 'JobSeeker',
        ]);

    }
}
