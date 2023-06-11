<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


if (!Schema::hasTable('social_accounts')) {

    Schema::create('social_accounts', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('user_id');
        $table->string('provider_id');
        $table->string('provider');
        $table->string('token');
        $table->timestamps();
        $table->unsignedInteger('created_by')->nullable()->index();
        $table->unsignedInteger('updated_by')->nullable()->index();
        $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('CASCADE');
    });

}
