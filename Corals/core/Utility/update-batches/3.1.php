<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasColumn('utility_list_of_values', 'label')) {
    Schema::table('utility_list_of_values', function (Blueprint $table) {
        $table->string('label')->after('code')->nullable();
    });
}

if (!Schema::hasColumn('utility_list_of_values', 'hidden')) {
    Schema::table('utility_list_of_values', function (Blueprint $table) {
        $table->boolean('hidden')->after('status')->default(false);
    });
}
