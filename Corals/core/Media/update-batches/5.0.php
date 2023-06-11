<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

Schema::table('media', function (Blueprint $table) {

    $table->string('uuid')
        ->after('model_id')
        ->nullable();

    $table->string('conversions_disk')
        ->after('disk')
        ->nullable();

});

DB::statement('update media set conversions_disk = disk');

Media::query()->each(function (Media $media) {
    $media->update(['uuid' => Str::uuid()]);
});
