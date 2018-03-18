<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->company
    ];
});
