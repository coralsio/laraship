<?php

return [
    'models' => [
        'notification_history' => [
            'presenter' => \Corals\User\Communication\Transformers\NotificationHistoryPresenter::class,
        ],
        'notification_template' => [
            'presenter' => \Corals\User\Communication\Transformers\NotificationTemplatePresenter::class,
            'resource_url' => 'notification-templates',
            'actions' => [
                'delete' => [],
                'activity_log' => [],
            ]
//            'translatable' => ['title', 'body']
        ],
        'notification' => [
            'presenter' => \Corals\User\Communication\Transformers\NotificationPresenter::class,
            'resource_url' => 'notifications',
            'actions' => [
                'delete' => [],
                'edit' => [],
                'toggle-read' => [
                    'icon_pattern' => ['pattern' => 'fa fa-fw [arg]', 'replace' => ['return $object->read() ? "fa-eye-slash" : "fa-eye";']],
                    'href_pattern' => ['pattern' => '[arg]/read-at-toggle', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' =>
                            ['return $object->read() ?  trans("Notification::labels.mark_as_unread") : trans("Notification::labels.mark_as_read");']
                    ],
                    'data' => [
                        'action' => 'get',
                        'table' => '#NotificationDataTable'
                    ]
                ]
            ]
        ],
    ],
    'supported_channels' => [
        'mail' => 'Notification::labels.supported_channels.mail',
        'database' => 'Notification::labels.supported_channels.database',
//        'slack' => 'Notification::labels.supported_channels.slack',
        'nexmo' => 'Notification::labels.supported_channels.nexmo',
    ],
    'supported_custom_channels' => [
        'mail' => 'Notification::labels.supported_channels.mail',
        //        'nexmo' => 'Notification::labels.supported_channels.nexmo',
    ],
    'user_preferences_options' => ['user_preferences' => 'Notification::labels.user_preferences_options.user_preferences'],
    'default_notification_image' => 'assets/corals/images/logo-square.png',
    'laravel_echo_domain' => env('LARAVEL_ECHO_SERVER_DOMAIN'),
    'broadcast_enabled' => env('BROADCAST_ENABLED'),
];
