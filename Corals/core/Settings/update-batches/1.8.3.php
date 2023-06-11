<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use \Carbon\Carbon;

if (!Schema::hasColumn('settings', 'category')) {
    Schema::table('settings', function (Blueprint $table) {
        $table->string('category')->after('type')->default('General');
    });
}


\DB::table('settings')->insert([
    [
        'code' => 'custom_js',
        'type' => 'TEXTAREA',
        'category' => 'Theme',
        'label' => 'Custom Frontend Theme Javascript',
        'value' => '',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'code' => 'custom_css',
        'type' => 'TEXTAREA',
        'category' => 'Theme',
        'label' => 'Custom Frontend Theme CSS',
        'value' => '',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'code' => 'custom_admin_js',
        'type' => 'TEXTAREA',
        'category' => 'Theme',
        'label' => 'Custom Admin Theme Javascript',
        'value' => '',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'code' => 'custom_admin_css',
        'type' => 'TEXTAREA',
        'category' => 'Theme',
        'label' => 'Custom Admin Theme CSS',
        'value' => '',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
]);
