<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_user = new User();
        $default_user->name = "Super User";
        $default_user->email = "info@netphone.co.tz";
        $default_user->phone = "255766457248";
        $default_user->password = Hash::make("info@netphone.co.tz");
        $default_user->admin = true;
        $default_user->save();

        $faker = Faker::create();

        for ($i = 0; $i < 11; $i++) {
            $email = $faker->safeEmail;
            User::insert([
                'name' =>  $faker->firstName . " " . $faker->lastName,
                'email' => $email,
                'phone' => substr($faker->e164PhoneNumber, 3, 11),
                'password' => Hash::make($email),
                'remember_token' => Str::random(10),
                'admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
