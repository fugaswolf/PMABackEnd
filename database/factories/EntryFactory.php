<?php

use Faker\Generator as Faker;

$factory->define(App\Entry::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'start_date' => $faker->date(),
        'end_date' => $faker->date(),
        'duration' => $faker->randomNumber(2),
        'description' => $faker->realText(),
        'activity_id' => $faker->randomNumber()
    ];
});
