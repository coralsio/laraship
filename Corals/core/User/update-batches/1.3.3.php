<?php

use Illuminate\Support\Facades\Schema;

use Corals\User\Communication\database\migrations\CreateNotificationTemplatesTable;
use Corals\User\Communication\database\migrations\CreateNotificationsTable;
use Corals\User\Communication\database\seeds\NotificationDatabaseSeeder;

if (!Schema::hasTable('notification_templates')) {

    $migrationObject = new CreateNotificationTemplatesTable;
    $migrationObject->up();

    $seeder = new NotificationDatabaseSeeder();
    $seeder->run();

}

if (!Schema::hasTable('notifications')) {

    $migrationObject = new CreateNotificationsTable;
    $migrationObject->up();

    $templatesSeeder = new \Corals\User\database\seeds\UserNotificationTemplatesSeeder();
    $templatesSeeder->run();

}



