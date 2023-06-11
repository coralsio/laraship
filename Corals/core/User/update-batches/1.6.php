<?php

\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->string('last_name')->nullable()->after('name');
});

