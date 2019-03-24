<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'customer_id' => $faker->numberBetween(),
        'name' => $faker->name,
        'comment' => $faker->realText,
        'budget' => $faker->randomNumber(2),
        'status' => $faker->boolean(50),
    ];
});
