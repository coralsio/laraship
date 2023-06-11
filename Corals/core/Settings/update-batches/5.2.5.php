<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('settings', function (Blueprint $table) {

    $table->boolean('is_public')
        ->after('editable')
        ->default(false);

});