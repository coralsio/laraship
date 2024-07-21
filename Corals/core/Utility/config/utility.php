<?php

return [
    'models' => [
        'model_option' => [
        ],
        'invite_friends' => [
            'resource_url' => 'utilities/invite-friends',
        ],
    ],
    'pre_defined_date' => [
        'options' => [
            'custom' => [
                'label' => 'Custom',
                'is_eval' => true,
                'from' => 'return now()->startOfMonth()->toDateString();',
                'to' => 'return now()->endOfMonth()->toDateString();'
            ],
            'year_to_today' => [
                'label' => 'YTD',
                'from' => [
                    'startOfYear' => 0,
                ],
                'to' => [
                    'endOfDay' => 0
                ]
            ],
            'month_to_today' => [
                'label' => 'MTD',
                'from' => [
                    'startOfMonth' => 0
                ],
                'to' => [
                    'endOfDay' => 0
                ]
            ],
            'previous_month' => [
                'label' => 'Previous Month',
                'is_eval' => true,
                'from' => 'return now()->subMonth()->startOfMonth()->toDateString();',
                'to' => 'return now()->subMonth()->endOfMonth()->toDateString();'
            ],
            'previous_year' => [
                'label' => 'Previous Year',
                'is_eval' => true,
                'from' => 'return now()->subYear()->startOfYear()->toDateString();',
                'to' => 'return now()->subYear()->endOfYear()->toDateString();'
            ],

            'current_and_previous_year' => [
                'label' => 'Current & Previous Year',
                'is_eval' => true,
                'from' => 'return now()->subYear()->startOfYear()->toDateString();',
                'to' => 'return now()->endOfYear()->toDateString();'
            ],

            'current_year' => [
                'label' => 'Current Year',
                'is_eval' => true,
                'from' => 'return now()->startOfYear()->toDateString();',
                'to' => 'return now()->endOfYear()->toDateString();'
            ],

            'previous_quarter' => [
                'label' => 'Previous Quarter',
                'is_eval' => true,
                'from' => 'return ($q = now()->subQuarter()->firstOfQuarter())->toDateString();',
                'to' => 'return $q->copy()->endOfQuarter()->toDateString();'
            ],

            'previous_and_current_quarter' => [
                'label' => 'Previous & Current Quarter',
                'is_eval' => true,
                'from' => 'return now()->subQuarter()->startOfQuarter()->toDateString();',
                'to' => 'return now()->endOfQuarter()->toDateString();'
            ],
            'current_quarter' => [
                'label' => 'Current Quarter',
                'is_eval' => true,
                'from' => 'return ($q = now()->firstOfQuarter())->toDateString();',
                'to' => 'return $q->copy()->endOfQuarter()->toDateString();'
            ],
            'next_quarter' => [
                'label' => 'Next Quarter',
                'is_eval' => true,
                'from' => 'return ($q = now()->addQuarter()->firstOfQuarter())->toDateString();',
                'to' => 'return $q->copy()->endOfQuarter()->toDateString();'
            ],

            'yesterday' => [
                'label' => 'Yesterday',
                'is_eval' => true,
                'from' => 'return now()->subDay()->startOfDay()->toDateString();',
                'to' => 'return now()->subDay()->endOfDay()->toDateString();'
            ],

            'today' => [
                'label' => 'Today',
                'is_eval' => true,
                'from' => 'return now()->startOfDay()->toDateString();',
                'to' => 'return now()->endOfDay()->toDateString();'
            ],

            'tomorrow' => [
                'label' => 'Tomorrow',
                'is_eval' => true,
                'from' => 'return now()->addDay()->startOfDay()->toDateString();',
                'to' => 'return now()->addDay()->endOfDay()->toDateString();'
            ],
            'last_week' => [
                'label' => 'Last Week',
                'is_eval' => true,
                'from' => 'return now()->subWeek()->startOfWeek()->toDateString();',
                'to' => 'return now()->subWeek()->endOfWeek()->toDateString();'
            ],

            'current_week' => [
                'label' => 'Current Week',
                'is_eval' => true,
                'from' => 'return now()->startOfWeek()->toDateString();',
                'to' => 'return now()->endOfWeek()->toDateString();'
            ],

            'last_month' => [
                'label' => 'Last Month',
                'is_eval' => true,
                'from' => 'return now()->subMonth()->startOfMonth()->toDateString();',
                'to' => 'return now()->subMonth()->endOfMonth()->toDateString();'
            ],

            'current_month' => [
                'label' => 'Current Month',
                'is_eval' => true,
                'from' => 'return now()->startOfMonth()->toDateString();',
                'to' => 'return now()->endOfMonth()->toDateString();'
            ],

        ]
    ]
];
