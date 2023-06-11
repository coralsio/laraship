<?php

\DB::table('notification_templates')->insert([
    [
        'name' => 'notifications.user.send_excel_file',
        'title' => '{table_id} export file has been generated',
        'friendly_name' => 'Export Generated File',
        'body' => '{"mail":"Please check the attached generated File for {table_id}","database":"{table_id} export is ready, check your inbox please."}',
        'extras' => '[]',
        'via' => '["mail","database"]',
    ]
]);
