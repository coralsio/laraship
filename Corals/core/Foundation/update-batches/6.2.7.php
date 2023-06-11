<?php

use Corals\User\Communication\Models\NotificationTemplate;

NotificationTemplate::updateOrCreate(['name' => 'notifications.import_status'], [
    'friendly_name' => 'Import Status',
    'title' => '{import_file_name} Import Status',
    'body' => [
        'mail' => '<p>Hi {user_name},</p><p>Here you are the {import_file_name} Import Status:</p><p>Success Imported Records: <b>{success_records_count}</b></p>
<p style="color:red;">Failed Imported Records: <b>{failed_records_count}</b><br/>{import_log_file}</p>',
        'database' => '<p>Here you are the {import_file_name} Import Status:</p><p>Success Imported Records: <b>{success_records_count}</b></p><p>Failed Imported Records: <b>{failed_records_count}</b><br/>{import_log_file}</p>',
    ],
    'via' => ["mail", "database"]
]);
