<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {

        /*
         * Table: menus
         */
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0);
            $table->string('key')->unique()->nullable();
            $table->string('url')->nullable();
            $table->string('active_menu_url')->nullable();
            $table->string('icon')->nullable();
            $table->text('roles')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->enum('target', ['_blank', '_self'])->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('properties')->nullable();

            $table->integer('order')->default(0);

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /*
    * Reverse the migrations.
    *
    * @return void
    */

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
