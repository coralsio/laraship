<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('gateway_status', function (Blueprint $table) {
    $table->string('status')->change();
    $table->string('status_type')->nullable()->after('message');
    $table->longText('properties')->nullable()->after('status');
});
