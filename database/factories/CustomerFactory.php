<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'c_name' => $faker->name,
        'company_name' => $faker->company,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->e164PhoneNumber,
        'address'=> $faker->address
    ];
});
