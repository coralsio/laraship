<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslatableTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!\Schema::hasTable('translatable_translations')) {

            Schema::create('translatable_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->string('translatable_type');
                $table->integer('translatable_id');
                $table->string('key');
                $table->longText('translation')->nullable();
                $table->text('properties')->nullable();
                $table->string('locale', 5);
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
        Schema::dropIfExists('translatable_translations');
    }
}
