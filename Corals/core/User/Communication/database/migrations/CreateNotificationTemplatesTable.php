<?php

namespace Corals\User\Communication\database\migrations;


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('event_name')->nullable();
            $table->string('friendly_name');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->text('extras')->nullable();
            $table->string('email_from')->nullable();
            $table->text('via')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('notification_preferences')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notification_preferences');
        });

        Schema::dropIfExists('notification_templates');
    }
}
