<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'cat_name' => $faker->colorName, //as like categoryy name
        'code' => $faker->ean8, // as like product code
        'description' => $faker->text($maxNbChars = 80) 
    ];
});
