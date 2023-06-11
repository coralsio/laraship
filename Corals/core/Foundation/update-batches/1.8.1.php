<?php

//Add Module Admin Permission
\DB::table('permissions')->insert([
    [
        'name' => 'Administrations::admin.core',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]
]);