<?php

\Corals\Settings\Models\Setting::firstOrCreate(['code' => 'login_background'],
    [
        'type' => 'TEXTAREA',
        'label' => 'Login Background',
        'value' => 'background: url(/media/demo/login_backgrounds/login_background.png);
background-repeat: repeat-y;
background-size: 100% auto;
background-position: center top;
background-attachment: fixed;',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]);

\Artisan::call('modelCache:flush');