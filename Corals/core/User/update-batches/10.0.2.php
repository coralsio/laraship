<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$tableNames = config('permission.table_names');

Schema::table($tableNames['roles'], function (Blueprint $table) {
    $table->json('can_manage_roles')->nullable()->after('properties');
});
