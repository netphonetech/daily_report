<?php

use App\Project;
use App\ProjectParticipant;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProjectParticipantTableSeeder extends Seeder
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
        $projects = Project::all()->pluck('id')->toArray();
        $users = User::all()->pluck('id')->toArray();
        foreach ($projects as $project) {
            for ($i = 0; $i < 4; $i++) {
                ProjectParticipant::insert([
                    'projectID' =>  $project,
                    'userID' => $faker->randomElement($users),
                    'role' =>  $faker->realText($maxNbChars = 10, $indexSize = 2),
                    'performance' =>  $faker->realText($maxNbChars = 10, $indexSize = 2),
                    'status' => $faker->randomElement($statuses),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
