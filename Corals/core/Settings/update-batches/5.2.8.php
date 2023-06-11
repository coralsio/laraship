<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('settings', function (Blueprint $table) {
    $table->integer('display_order')->after('category')->nullable();
});