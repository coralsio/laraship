<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

if (!Schema::hasColumn('media', 'generated_conversions')) {
    Schema::table('media', function (Blueprint $table) {
        $table->text('generated_conversions')->nullable();
    });
}

Media::query()->where(function ($query) {
    $query->whereNull('generated_conversions')
        ->orWhere('generated_conversions', '')
        ->orWhereRaw("JSON_TYPE(generated_conversions) = 'NULL'");
})->whereRaw("JSON_LENGTH(custom_properties) > 0")
    ->update([
        'generated_conversions' => DB::raw("JSON_EXTRACT(custom_properties, '$.generated_conversions')"),
        'custom_properties' => DB::raw("JSON_REMOVE(custom_properties, '$.generated_conversions')")
    ]);
