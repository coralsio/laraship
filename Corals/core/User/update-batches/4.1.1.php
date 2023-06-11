<?php

\Illuminate\Support\Facades\DB::table('permissions')->insert([
    [
        'name' => 'Administrations::admin.media',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
]);
