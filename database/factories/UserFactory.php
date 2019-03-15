<?php

use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Holiday::class, function (Faker $faker) {

    $start_end_date = $faker->dateTimeBetween($startDate = '-100 days', $endDate = '+100 days', $timezone = 'Asia/Tokyo')->format('Y-m-d');
    return [
        'user_id' => function() {
            return User::inRandomOrder()->first()->id;
        },
        'start_date' => $start_end_date,
        'end_date' => $start_end_date
    ];
});

$factory->define(App\Appointment::class, function (Faker $faker) {

    $start_end_date = $faker->dateTimeBetween($startDate = '-100 days', $endDate = '+100 days', $timezone = 'Asia/Tokyo')->format('Y-m-d');

    $hour = rand(10, 20);
    $minute = rand(0, 59);
    $second = 0;
    $tz = "Asia/Tokyo";
    $fakeTime = Carbon::createFromTime($hour, $minute, $second, $tz);

    $carbonTime = new Carbon($fakeTime);
    $addTime = rand(1, 3);

    return [
        'title' => ucfirst($faker->realText(15)),
        'description' => $faker->realText(200),
        'start_date' => $start_end_date,
        'start_time' => $fakeTime,
        'end_date' => $start_end_date,
        'end_time' => $carbonTime->addHour($addTime),
        'color' => $faker->randomElement(['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107']),
        'text_color' => $faker->randomElement(['#fff', '#343a40']),
    ];
});

