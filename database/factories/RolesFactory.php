<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['admin', 'manager', 'consultant', 'hr', 'worker']),
        'guard_name' => 'web'
    ];
});
