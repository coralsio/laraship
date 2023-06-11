<?php

return [
    'api_version' => env('CORALS_API_VERSION', 'v1'),
    'cache_ttl' => env('DEFAULT_CACHE_TTL', '1440'),
    'slack' => [
        'exception_channels' => array_filter(explode(',', env('SLACK_EXCEPTION_CHANNELS'))),
    ],
    'query_builder_enabled' => env('QUERY_BUILDER_FILTER_ENABLED', false),
    'query_builder_condition_types' => [
        'text' => [
            'equal',
            'not_equal',
            'begins_with',
            'not_begins_with',
            'contains',
            'not_contains',
            'ends_with',
            'not_ends_with',
            'is_empty',
            'is_not_empty',
            'is_null',
            'is_not_null'
        ],
        'date' => [
            'equal',
            'not_equal',
            'is_null',
            'is_not_null',
            'less',
            'less_or_equal',
            'greater',
            'greater_or_equal',
            'between',
            'not_between',
        ],
        'boolean' => [
            'equal',
            'is_null',
            'is_not_null'
        ],
        'select' => [
            'equal',
            'is_null',
            'is_not_null',
            'in',
            'not_in'
        ]
    ],
    'csv_delimiter' => env('CSV_DELIMITER', ','),
];
