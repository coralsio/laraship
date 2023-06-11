<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (Schema::hasColumn('custom_fields', 'custom_field_setting_id')) {
    rescue(function () {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->dropForeign('custom_fields_custom_field_setting_id_foreign');
        });
    });
    rescue(function () {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->dropColumn('custom_field_setting_id');
        });
    });
}