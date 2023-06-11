<?php


use Carbon\Carbon;

\DB::table('permissions')->insert([
    [
        'name' => 'Utility::webhook.view',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Utility::webhook.process',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
]);
