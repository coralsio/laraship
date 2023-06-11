<?php

return [
    'notification_template' => [
        'name' => 'Name',
        'friendly_name' => 'Friendly Name',
        'title' => 'Title',
        'title_help' => 'The title of notification to be displayed.',
        'via' => 'Via',
        'via_help' => "Notification Channels. If 'User Preferences' was chosen, notification will be sent for both forced and user preferred channels.",
        'read_at' => 'Read at',
        'roles' => 'Roles',
        'roles_help' => 'Only users with this role can customize channel for this notification.',
        'bcc_roles' => 'Send notification to Roles (BCC)',
        'bcc_roles_help' => 'BCC will be sent to all users in selected roles.',
        'bcc_users' => 'Send notification to Users (BCC)',
        'bcc_users_help' => 'BCC will be sent to selected users.',
        'body' => 'Body *',
        'email_from' => 'Email from',
        'email_from_help' => 'when filled it will override the default email from',
    ]
];
