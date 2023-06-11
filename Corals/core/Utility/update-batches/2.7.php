<?php

use \Illuminate\Support\Facades\Schema;
use \Illuminate\Database\Schema\Blueprint;

Schema::table('utility_list_of_values', function (Blueprint $table) {
    $table->string('label')->after('code')->nullable();
    $table->boolean('hidden')->after('status')->default(false);
});