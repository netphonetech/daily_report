<?php

use App\Project;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $statuses = [0, 1];
        for ($i = 0; $i < 100; $i++) {
            Project::insert([
                'name' =>  $faker->realText($maxNbChars = 200, $indexSize = 2),
                'start_date' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
                'expected_end_date' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
                'end_date' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
                'description' =>  $faker->realText($maxNbChars = 1000, $indexSize = 2),
                'status' => $faker->randomElement($statuses),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
