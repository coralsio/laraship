<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('http_log', function (Blueprint $table) {
    $table->string('response_code')
        ->nullable()
        ->after('method')
        ->index();
});
