<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Purchase;
use Faker\Generator as Faker;

$factory->define(Purchase::class, function (Faker $faker) {
    return [
        'product_id' => $faker->numberBetween($min = 3, $max = 20),
        'supplier_id' => $faker->numberBetween($min = 3, $max = 20),
        'product_quantity' => $faker->numberBetween($min = 25, $max = 600),
        'retail_price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 100.00, $max = 1000.00),
        'sale_price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 100.00, $max = 1000.00),
    ];
});
