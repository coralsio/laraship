<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('users', function (Blueprint $table) {
    $table->unsignedInteger('owner_id')->nullable();

    $table->foreign('owner_id')->references('id')->on('users');
});
