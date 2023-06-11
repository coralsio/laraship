<?php

use Carbon\Carbon;

use Illuminate\Database\Schema\Blueprint;

if (!\Schema::hasColumn('utility_locations', 'type')) {

    \Schema::table('utility_locations', function (Blueprint $table) {
        $table->string('type')->nullable()->index()->after('module');
        $table->unsignedInteger('parent_id')->nullable()->default(0)->after('type');

    });


    \DB::table('settings')->insert([
        [
            'code' => 'utility_location_types',
            'type' => 'SELECT',
            'label' => 'Location Types',
            'value' => '{"city":"City","state":"State","country":"Country"}',
            'editable' => 1,
            'hidden' => 0,
            'category' => 'Utilities',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
    ]);
}