<?php

return [
    'is_enabled' => env('HTTP_LOGGER_ENABLED', false),
    'models' => [
        'http_log' => [
            'resource_url' => 'http-logs',
            'presenter' => \Corals\Activity\HttpLogger\Transformers\HttpLogPresenter::class,
            'methods' => [
                'POST' => 'POST',
                'PUT' => 'PUT',
                'PATCH' => 'PATCH',
                'DELETE' => 'DELETE',
                'GET' => 'GET',
            ],
        ]
    ],
    /*
     * The log profile which determines whether a request should be logged.
     * It should implement `LogProfile`.
     */
    'log_profile' => \Corals\Activity\HttpLogger\Classes\LogNonGetRequests::class,

    /*
     * The log writer used to write the request to a log.
     * It should implement `LogWriter`.
     */
    'log_writer' => \Corals\Activity\HttpLogger\Classes\LogWriter::class,

    'delete_records_older_than_days' => env('HTTP_LOGGER_PURGE_BEFORE_DAYS', 15),

    'get_included' => env('HTTP_LOGGER_GET_INCLUDED', false),
    /*
     * Filter out body fields which will never be logged.
     */
    'except' => [
        'password',
        'password_confirmation',
    ],

    'exclude_url_paths' => [
        'http-logs'
    ],
];
