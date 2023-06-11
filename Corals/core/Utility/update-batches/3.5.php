<?php


use Carbon\Carbon;

\DB::table('settings')->insert([
    [
        'code' => 'utility_google_address_country',
        'type' => 'TEXT',
        'category' => 'Utilities',
        'label' => 'Google address Search Country',
        'value' => '',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'code' => 'utility_google_address_default_search_radius',
        'type' => 'NUMBER',
        'category' => 'Utilities',
        'label' => 'Default Search Radius',
        'value' => 50,
        'editable' => 1,
        'hidden' => 0,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
]);