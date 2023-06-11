<?php


\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {

    $table->string('classification')->index()->nullable()->after('job_title');

});

\Corals\Settings\Models\Setting::updateOrCreate(['code' => 'customer_classifications',], [
    'type' => 'SELECT',
    'category' => 'User',
    'label' => 'Customer Classifications',
    'value' => json_encode(['standard' => 'Standard', 'silver' => 'Silver', 'gold' => 'Gold']),
    'editable' => 1,
    'hidden' => 0,
    'created_at' => now(),
    'updated_at' => now(),
]);

