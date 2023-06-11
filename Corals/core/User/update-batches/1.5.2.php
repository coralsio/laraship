<?php

use Carbon\Carbon;

\DB::table('settings')->insert([
    [
        'code' => 'cookie_consent',
        'type' => 'BOOLEAN',
        'category' => 'User',
        'label' => 'Enable Cookie Consent',
        'value' => 'false',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'code' => 'cookie_consent_config',
        'type' => 'TEXTAREA',
        'category' => 'User',
        'label' => 'Cookie Consent Configuration',
        'value' => '{
                        type: "opt-in",
                        position: "bottom",
                        palette: { "popup": { "background": "#252e39" }, "button": { "background": "#14a7d0", padding: "5px 50px" } }
            
                    }',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]
]);