<?php

Illuminate\Support\Facades\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->text('properties')->nullable()->after('payment_method_token');
});