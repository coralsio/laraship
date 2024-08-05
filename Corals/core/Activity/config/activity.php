<?php

return [
    'models' => [
        'activity' => [
            'presenter' => \Corals\Activity\Transformers\ActivityPresenter::class,
            'resource_url' => 'activities',
            'actions' => [
                'edit' => [],
            ]
        ],
    ],
    'system_monitor_enabled' => env('SYSTEM_MONITOR_ENABLED', false)
];
