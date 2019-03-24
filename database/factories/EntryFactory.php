<?php

use Faker\Generator as Faker;

$factory->define(App\Entry::class, function (Faker $faker) {
    return [
        'id' => $faker->numberBetween(),
        'user_id' => $faker->randomNumber(),
        'start_date' => $faker->date(),
        'end_date' => $faker->date(),
        'duration' => $faker->randomNumber(2), // het verschil tussen start en end date? hoe doe ik dit? maakt niet uit tis toch maar dummy data
        'description' => $faker->realText(),
        'activity_id' => $faker->randomNumber()
    ];
});
