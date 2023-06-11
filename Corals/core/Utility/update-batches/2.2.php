<?php

use Illuminate\Database\Schema\Blueprint;

$tablesNeedPropertiesColumn = [
    'utility_ratings', 'utility_wishlists',
    'utility_locations', 'utility_schedules', 'utility_comments'
];

foreach ($tablesNeedPropertiesColumn as $tableName) {
    if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'properties')) {
        Schema::table($tableName, function (Blueprint $table) {
            $table->text('properties')->nullable();
        });
    }
}