<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Corals\Modules\Subscriptions\Models\Subscription::class, function (Faker $faker) {
    return [
        'plan_id' => random_int(2, 5),
        'subscription_reference' => \Str::random(10)
    ];
});
