<?php

\DB::table('settings')->insert([
    [
        'code' => 'google_tag_manager_id',
        'type' => 'TEXT',
        'category' => 'General',
        'label' => 'Google Tag Manager Id',
        'value' => 'GTM-M78BQH6',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
]);
