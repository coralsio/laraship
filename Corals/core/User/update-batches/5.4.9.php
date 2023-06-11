<?php

\DB::table('settings')->insert([
    [
        'code' => 'registration_enabled',
        'type' => 'BOOLEAN',
        'category' => 'User',
        'label' => 'Enable Registration',
        'value' => 'true',
        'editable' => 1,
        'hidden' => 0,
        'is_public' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
]);