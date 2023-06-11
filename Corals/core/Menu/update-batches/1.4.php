<?php


if ( !\Schema::hasColumn('menus', 'properties')) {
    Illuminate\Support\Facades\Schema::table('menus', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->text('properties')->nullable()->after('status');
    });
}

