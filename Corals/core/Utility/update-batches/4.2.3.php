<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasColumn('utility_tags', 'source_id')) {
    Schema::table('utility_tags', function (Blueprint $table) {
        $name = 'source';

        $table->unsignedBigInteger("{$name}_id")->nullable()->after('slug');
        $table->string("{$name}_type")->nullable()->after('slug');


        $table->index(["{$name}_type", "{$name}_id"], null);
    });
}
