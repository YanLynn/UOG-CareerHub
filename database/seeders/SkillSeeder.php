<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            ['name' => 'JavaScript'],
            ['name' => 'Python'],
            ['name' => 'PHP'],
            ['name' => 'Java'],
            ['name' => 'SQL'],
            ['name' => 'Laravel'],
            ['name' => 'Vue.js'],
            ['name' => 'React'],
            ['name' => 'Node.js'],
            ['name' => 'C++'],
            ['name' => 'C#'],
            ['name' => 'UI/UX Design'],
            ['name' => 'Digital Marketing'],
            ['name' => 'Project Management'],
            ['name' => 'Data Analysis'],
            ['name' => 'Machine Learning'],
            ['name' => 'Cybersecurity'],
            ['name' => 'SEO'],
            ['name' => 'Cloud Computing'],
            ['name' => 'Networking'],
            ['name' => 'TypeScript'],
            ['name' => 'Next.js'],
            ['name' => 'Express.js'],
            ['name' => 'Tailwind CSS'],
            ['name' => 'Bootstrap'],
            ['name' => 'Git'],
            ['name' => 'Docker'],
            ['name' => 'Kubernetes'],
            ['name' => 'AWS'],
            ['name' => 'Azure'],
            ['name' => 'Firebase'],
            ['name' => 'Agile Methodology'],
            ['name' => 'Scrum'],
            ['name' => 'JIRA'],
            ['name' => 'Figma'],
            ['name' => 'Adobe XD'],
            ['name' => 'Penetration Testing'],
            ['name' => 'Blockchain'],
            ['name' => 'Artificial Intelligence'],
            ['name' => 'Natural Language Processing'],
        ];

        DB::table('skills')->insert($skills);
    }
}
