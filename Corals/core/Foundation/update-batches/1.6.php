<?php


\DB::table('settings')->insert([
    [
        'code' => 'supported_languages',
        'type' => 'SELECT',
        'category' => 'General',
        'label' => 'Supported system languages',
        'value' => json_encode(['en' => 'English', 'pt-br' => 'Brazilian', 'ar' => 'Arabic']),
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
]);


