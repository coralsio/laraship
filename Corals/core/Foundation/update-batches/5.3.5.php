<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('gateway_status')) {
    Schema::create('gateway_status', function (Blueprint $table) {
        $table->increments('id');
        $table->string('gateway');
        $table->string('object_type');
        $table->unsignedInteger('object_id');
        $table->string('object_reference')->nullable();
        $table->text('message')->nullable();
        $table->enum('status', ['CREATED', 'UPDATED', 'CREATE_FAILED', 'UPDATE_FAILED', 'NA', 'DELETED', 'DELETE_FAILED'])->default('NA');

        $table->unsignedInteger('created_by')->nullable()->index();
        $table->unsignedInteger('updated_by')->nullable()->index();

        $table->softDeletes();
        $table->timestamps();
    });
}
