<?php

/**
 * Authy configuration & API credentials.
 */

return [
    'mode' => env('AUTHY_MODE', 'live'),
    // Can be either 'live' or 'sandbox'. If empty or invalid 'live' will be used
    'sandbox' => [
        'key' => env('AUTHY_TEST_KEY', ''),
    ],
    'live' => [
        'key' => env('AUTHY_LIVE_KEY', ''),
    ],
    'default_channel' => env('AUTHY_DEFAULT_CHANNEL', 'sms'),
    'supported_channels' => ['sms' => 'SMS', 'phoneCall' => 'Phone Call']
];