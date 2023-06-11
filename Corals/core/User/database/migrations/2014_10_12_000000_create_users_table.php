<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->text('address')->nullable();
            $table->string('job_title')->nullable();
            $table->string('status')->default('active');
            $table->string('classification')->index()->nullable();
            // Two-Factor Authentication Columns...
            $table->string('phone_country_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('two_factor_options')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->string('integration_id')->nullable();
            $table->string('gateway')->nullable();

            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('payment_method_token')->nullable();

            $table->text('properties')->nullable();

            $table->rememberToken();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
        });

        (new \Corals\User\Communication\database\migrations\CreateNotificationTemplatesTable())->up();
        (new \Corals\User\Communication\database\migrations\CreateNotificationsTable())->up();

        Schema::create('social_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('provider_id');
            $table->string('provider');
            $table->string('token');
            $table->text('properties')->nullable();
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');
        });

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

            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_accounts');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('users');
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('groups');
    }
}
