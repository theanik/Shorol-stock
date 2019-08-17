<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Supplier;
use Faker\Generator as Faker;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'company_name' => $faker->company,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->e164PhoneNumber,
        'address'=> $faker->address
    ];
});
