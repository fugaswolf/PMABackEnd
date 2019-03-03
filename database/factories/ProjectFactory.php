<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->realText,
        'status' => now(),
        'start_date' => date($format = 'd-m-Y', $max = 'now'),
        'end_date' => date($format = 'd-m-Y', $max = 'now')
    ];
});
