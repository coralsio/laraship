<?php

use Illuminate\Database\Schema\Blueprint;

\Illuminate\Support\Facades\Schema::table('utility_tags', function (Blueprint $table) {
    $table->text('properties')->nullable()->after('module');
});

\Illuminate\Support\Facades\Schema::table('utility_attributes', function (Blueprint $table) {
    $table->text('properties')->nullable()->after('required');
});