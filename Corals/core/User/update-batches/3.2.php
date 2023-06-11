<?php

use Illuminate\Database\Schema\Blueprint;

$tablesNeedPropertiesColumn = [
    'users', 'roles', 'permissions'
];

foreach ($tablesNeedPropertiesColumn as $tableName) {
    if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'properties')) {
        Schema::table($tableName, function (Blueprint $table) {
            $table->text('properties')->nullable();
        });
    }
}