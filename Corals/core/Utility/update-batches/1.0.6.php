<?php

\Schema::table('utility_ratings', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->string('title')->nullable()->change();
    $table->string('body')->nullable()->change();
    $table->string('criteria')->after('author_type')->nullable();
});
