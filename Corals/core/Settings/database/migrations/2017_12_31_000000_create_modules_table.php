<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code')->unique()->index();
            $table->boolean('enabled')->default(false);
            $table->boolean('installed')->default(false);
            $table->string('installed_version')->nullable();
            $table->integer('load_order')->default(0);
            $table->string('provider')->nullable();
            $table->string('folder')->nullable();
            $table->text('properties')->nullable();
            $table->enum('type', ['core', 'module', 'payment']);
            $table->text('notes')->nullable();
            $table->text('license_key')->nullable();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
