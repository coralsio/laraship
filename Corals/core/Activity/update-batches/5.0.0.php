<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('activity_log', function (Blueprint $table) {

    if (!Schema::hasColumn('activity_log', 'batch_uuid')) {
        $table->uuid('batch_uuid')->nullable()->after('properties');
    }

    if (!Schema::hasColumn('activity_log', 'event')) {
        $table->string('event')->nullable()->after('subject_type');
    }

});
