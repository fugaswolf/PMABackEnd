<?php

use Faker\Generator as Faker;

$factory->define(App\Activity::class, function (Faker $faker) {
    return [
        'project_id' => $faker->idNumber,
        'name' => $faker->name,
        'comment' => $faker->realText,
        'status' => $faker->boolean(50),
    ];
});
