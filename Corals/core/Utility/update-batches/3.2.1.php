<?php

\Illuminate\Support\Facades\Schema::table('utility_comments', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->boolean('is_private')->after('author_id')->default(false);
});

\DB::table('permissions')->insert([
    [
        'name' => 'Utility::comment.can_see_private_comments',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
