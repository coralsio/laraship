<?php

use Illuminate\Database\Schema\Blueprint;

\Schema::create('utility_avg_ratings', function (Blueprint $table) {
    $table->increments('id');

    $table->integer('count');
    $table->decimal('avg');

    $table->morphs('avgreviewable');

    $table->text('criterias')->nullable();
    $table->text('properties')->nullable();

    $table->unsignedInteger('created_by')->nullable()->index();
    $table->unsignedInteger('updated_by')->nullable()->index();

    $table->softDeletes();
    $table->timestamps();
});