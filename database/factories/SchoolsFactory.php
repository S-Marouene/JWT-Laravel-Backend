<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Schools;
use Faker\Generator as Faker;

$factory->define(Schools::class, function (Faker $faker) {
    return [
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
        'Name' => $faker->firstName,
        'phone' => $faker->numerify('########'),
        'email' => $faker->email,
        'address' => $faker->address
    ];
});
