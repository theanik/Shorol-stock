<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Sales;
use Faker\Generator as Faker;

$factory->define(Sales::class, function (Faker $faker) {
    return [
        'purchases_id' => $faker->numberBetween($min = 3, $max = 20),
        'customer_id' => $faker->numberBetween($min = 3, $max = 20),
        'quantity' => $faker->numberBetween($min = 25, $max = 600),
        't_price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 100.00, $max = 1000.00)
    ];
});
