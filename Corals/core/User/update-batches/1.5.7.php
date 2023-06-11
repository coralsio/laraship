<?php

//add new settings to the User category
\Corals\Settings\Models\Setting::updateOrCreate(['code' => 'available_registration_roles',], [
    'type' => 'SELECT',
    'category' => 'User',
    'label' => 'Available registration roles',
    'value' => json_encode(['member' => 'Member']),
    'editable' => 1,
    'hidden' => 0,
    'created_at' => now(),
    'updated_at' => now(),
]);
