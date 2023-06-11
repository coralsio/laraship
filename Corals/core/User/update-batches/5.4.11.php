<?php

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('groups', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name')->unique();
    $table->text('properties')->nullable();
    $table->unsignedInteger('created_by')->nullable()->index();
    $table->unsignedInteger('updated_by')->nullable()->index();
    $table->softDeletes();
    $table->timestamps();
});

Schema::create('group_user', function (Blueprint $table) {
    $table->integer('group_id')->unsigned();
    $table->integer('user_id')->unsigned();
    $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});


\DB::table('permissions')->insert([
    [
        'name' => 'User::user.restore',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'User::user.hardDelete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'User::group.view',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'User::group.create',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'User::group.update',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'User::group.delete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
]);