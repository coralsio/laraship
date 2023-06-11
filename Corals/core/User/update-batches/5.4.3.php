<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('notifications_history', function (Blueprint $table) {

    $table->bigIncrements('id');
    $table->nullableMorphs('model');
    $table->string('notification_name');
    $table->text('notifiables')->nullable();
    $table->text('channels')->nullable();
    $table->text('body')->nullable();
    $table->text('properties')->nullable();

    $table->unsignedInteger('created_by')->nullable()->index();
    $table->unsignedInteger('updated_by')->nullable()->index();

    $table->timestamps();
});
