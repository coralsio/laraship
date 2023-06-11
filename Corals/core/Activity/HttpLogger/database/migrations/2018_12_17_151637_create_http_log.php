<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHttpLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('http_log', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('ip')->nullable()->index();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('uri')->nullable()->index();
            $table->string('method')->nullable();
            $table->string('response_code')->nullable()->index();
            $table->text('headers')->nullable();
            $table->text('body')->nullable();
            $table->text('response')->nullable();
            $table->text('files')->nullable();
            $table->text('properties')->nullable();

            $table->timestamps();

            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('http_log');
    }
}
