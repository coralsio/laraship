<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('utility_invites', function (Blueprint $table) {
    $table->id();

    $table->string('code')->unique();
    $table->string('email');
    $table->unsignedInteger('inviter_id');

    $table->foreign('inviter_id')
        ->references('id')
        ->on('users')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
    $table->text('subject');
    $table->string('message');
    $table->boolean('accepted')->default(0);
    
    $table->text('properties')->nullable();
    $table->auditable();
    $table->softDeletes();
    $table->timestamps();
});
