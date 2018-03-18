<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CustomerPricingRule::class, function (Faker $faker) {
    return [
        'display_name' => $faker->sentence
    ];
});
