<?php



use Illuminate\Database\Schema\Blueprint;

$tablesNeedPropertiesColumn = [
    'settings', 'model_settings', 'countries', 'modules', 'custom_field_settings'
];

foreach ($tablesNeedPropertiesColumn as $tableName) {
    if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'properties')) {
        Schema::table($tableName, function (Blueprint $table) {
            $table->text('properties')->nullable();
        });
    }
}

if (Schema::hasTable('custom_field_settings')) {
    Schema::table('custom_field_settings', function (Blueprint $table) {
        $dropColumns = [
            'type', 'name', 'label', 'required', 'options', 'options_options',
            'custom_attributes', 'default_value', 'validation_rules', 'status'
        ];
        foreach ($dropColumns as $column) {
            $table->dropColumn($column);
        }

        $table->text('fields')->after('model');
    });
}


\DB::table('permissions')->insert([
    'name' => 'Administrations::admin.core',
    'guard_name' => config('auth.defaults.guard'),
    'created_at' => \Carbon\Carbon::now(),
    'updated_at' => \Carbon\Carbon::now(),
]);

