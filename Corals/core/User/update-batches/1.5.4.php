<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$tableNames = config('permission.table_names');

Schema::table($tableNames['roles'], function (Blueprint $table) {
    $table->boolean('disable_login')->default(0)->after('subscription_required');
    $table->string('redirect_url')->nullable()->after('subscription_required');
    $table->string('dashboard_url')->nullable()->after('subscription_required');
    $table->string('dashboard_theme')->nullable()->after('subscription_required');
});
