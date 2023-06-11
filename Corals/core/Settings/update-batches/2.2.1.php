<?php

//Add Module Permission
\DB::table('permissions')->insert([
    [
        'name' => 'Administrations::admin.setting',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]
]);