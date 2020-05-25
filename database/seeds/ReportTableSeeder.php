<?php

use App\Project;
use App\Report;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ReportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $projects = Project::all()->pluck('id')->toArray();
        $users = User::all()->pluck('id')->toArray();
        for ($i = 0; $i < 100; $i++) {
            Report::insert([
                'userID' => $faker->randomElement($users),
                'projectID' => $faker->randomElement($projects),
                'date' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
                'main_task' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'sub_task' => $faker->realText($maxNbChars = 88, $indexSize = 2),
                'next_sub_task' => $faker->realText($maxNbChars = 88, $indexSize = 2),
                'extra_notes' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
