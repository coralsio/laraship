<?php

use Illuminate\Support\Facades\Schema;


Schema::table('custom_field_settings', function($table) {
    $table->text('options_options');
});