<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewayStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('gateway_status')) {
            Schema::create('gateway_status', function (Blueprint $table) {
                $table->increments('id');
                $table->string('gateway');
                $table->string('object_type');
                $table->unsignedInteger('object_id');
                $table->string('object_reference')->nullable();
                $table->text('message')->nullable();
                $table->string('status_type')->nullable();
                $table->string('status')->default('NA');
                $table->longText('properties')->nullable();

                $table->unsignedInteger('created_by')->nullable()->index();
                $table->unsignedInteger('updated_by')->nullable()->index();

                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gateway_status');
    }
}
