<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create specific users
        User::create([
            'name'       => 'Admin User',
            'email'      => 'admin@gmail.com',
            'password'   => Hash::make('password'),
            'role'       => 'Admin',
            'status'     => $faker->randomElement(['active', 'inactive']),
            'last_login' => $faker->dateTimeBetween('-1 month', 'now'),
        ]);

        User::create([
            'name'       => 'Employer User',
            'email'      => 'employer@gmail.com',
            'password'   => Hash::make('password'),
            'role'       => 'Employer',
            'status'     => $faker->randomElement(['active', 'inactive']),
            'last_login' => $faker->dateTimeBetween('-1 month', 'now'),
        ]);

        User::create([
            'name'       => 'JobSeeker User',
            'email'      => 'jobseeker@gmail.com',
            'password'   => Hash::make('password'),
            'role'       => 'JobSeeker',
            'status'     => $faker->randomElement(['active', 'inactive']),
            'last_login' => $faker->dateTimeBetween('-1 month', 'now'),
        ]);

        // Create 7 random users
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name'       => $faker->name,
                'email'      => $faker->unique()->safeEmail,
                'password'   => Hash::make('password'),
                'role'       => $faker->randomElement(['Employer', 'JobSeeker']),
                'status'     => $faker->randomElement(['active', 'inactive']),
                'last_login' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);
        }
    }
}
