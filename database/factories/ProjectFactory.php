<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'customer_id' => $faker->idNumber,
        'name' => $faker->name,
        'comment' => $faker->realText,
        'budget' => $faker->randomNumber(2),
        'status' => $faker->boolean(50),
        'start_date' => date($format = 'd-m-Y', $max = 'now'),
        'end_date' => date($format = 'd-m-Y', $max = 'now')
    ];
});
