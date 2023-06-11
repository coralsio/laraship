<?php


if ( !\Schema::hasColumn('roles', 'properties')) {
    Illuminate\Support\Facades\Schema::table('roles', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->text('properties')->nullable()->after('dashboard_theme');
    });
}

//Add Module Permission
\DB::table('permissions')->insert([
    [
        'name' => 'Administrations::admin.user',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]
]);