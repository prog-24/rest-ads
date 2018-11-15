<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $token = sha1($faker->password);
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'auth_token' => $token
    ];
});

$factory->define(App\Ad::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->company,
        'description' => $faker->sentence,
        'price' => $faker->randomFloat(2, 1, 30)
    ];
});
