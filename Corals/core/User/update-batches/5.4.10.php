<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('notification_templates', function (Blueprint $table) {
    $table->string('email_from')->nullable()->after('via');
});

