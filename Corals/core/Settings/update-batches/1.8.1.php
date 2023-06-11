<?php

use Illuminate\Support\Facades\Schema;

if (!Schema::hasColumn('custom_field_settings', 'custom_attributes')) {
    Schema::table('custom_field_settings', function ($table) {
        $table->text('custom_attributes')->after('options_options');
    });
}