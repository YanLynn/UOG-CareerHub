<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Faker\Factory as Faker;
class EmployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $userIds = User::where('role', 'Employer')->pluck('id')->toArray();
        $countryIds = DB::table('countries')->pluck('id')->toArray();
        foreach ($userIds as $userId) {
            DB::table('employers')->insert([
                'user_id'          => $userId,
                'company_name'     => $faker->name . $userId,
                'company_website'  => 'https://example.com/company-' . $userId,
                'company_image'    => $faker->randomElement([null, 'https://res.cloudinary.com/example/image-' . $userId . '.jpg']),
                'industry'         => $faker->randomElement(['IT', 'Healthcare', 'Finance', 'Marketing', 'Education']),
                'company_size'     => $faker->randomElement(['Small', 'Medium', 'Large']),
                'company_description' => $faker->sentence(10),
                'founded_year'     => $faker->year(),
                'country_id' => $countryIds[array_rand($countryIds)],
                'contact_email'    => $faker->companyEmail,
                'contact_phone'    => $faker->phoneNumber,
                'linkedin_url'     => 'https://linkedin.com/company-' . $userId,
                'twitter_url'      => 'https://twitter.com/company-' . $userId,
                'facebook_url'     => 'https://facebook.com/company-' . $userId,
                'status'           => $faker->randomElement(['Active', 'Inactive']),
                'verified'         => $faker->boolean(80),
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);
        }

    }
}
