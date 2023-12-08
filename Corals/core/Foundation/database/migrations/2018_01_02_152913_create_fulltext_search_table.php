<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFulltextSearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!\Schema::hasTable('fulltext_search')) {

            Schema::create('fulltext_search', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('indexable_id');
                $table->string('indexable_type');
                $table->text('indexed_title');
                $table->text('indexed_content');
                $table->text('properties')->nullable();
                $table->unique(['indexable_type', 'indexable_id']);
                $table->engine = 'MyISAM';

                $table->unsignedInteger('created_by')->nullable()->index();
                $table->unsignedInteger('updated_by')->nullable()->index();

                $table->timestamps();
            });

            if (\Illuminate\Support\Facades\DB::connection()->getConfig()['name'] == 'mysql') {
                \DB::statement('ALTER TABLE fulltext_search ADD FULLTEXT fulltext_title(indexed_title)');
                \DB::statement('ALTER TABLE fulltext_search ADD FULLTEXT fulltext_title_content(indexed_title, indexed_content)');
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('fulltext_search');
    }
}
