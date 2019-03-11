<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
       'division_id' => $faker->randomNumber(1),
       'name' => $faker->name,
       'comment' => $faker->realText,
       'country' => $faker->country,
       'address' => $faker->address,
       'phone' => $faker->phoneNumber,
       'email' => $faker->email,
       'status' => $faker->boolean(25)
    ];
});
