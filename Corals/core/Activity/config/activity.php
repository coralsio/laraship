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
    ]
];
