<?php

if (!\Schema::hasColumn('notification_templates', 'event_name')) {
    Illuminate\Support\Facades\Schema::table('notification_templates', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->text('event_name')->after('name');
    });

    \DB::table('notification_templates')->update([
        'event_name' => DB::raw("name")
    ]);
}