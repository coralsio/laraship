<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('model_settings')) {

    Schema::create('model_settings', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('model_id');
        $table->string('model_type');
        $table->string('code')->index();
        $table->enum('cast_type', ['string', 'integer', 'boolean', 'float'])->default('string');
        $table->text('value')->nullable();
        $table->unsignedInteger('created_by')->nullable()->index();
        $table->unsignedInteger('updated_by')->nullable()->index();
        $table->timestamps();
    });
}