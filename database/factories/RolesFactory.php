<?php

use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;

// wacht wa wilt ge doen
// 5 rollen maken ? ka (wat wel gewerkt heeft op localhost lol)
// je hebt die lokaal wsl zelf gemaakt (via phpmyadmin?) idk xD
$factory->define(Role::class, function (Faker $faker) {
    return [
        'guard_name' => 'web'
    ];
});
