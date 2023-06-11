<?php

if (!\Schema::hasTable('translatable_translations')) {
    \Illuminate\Support\Facades\Schema::create('translatable_translations',
        function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('translatable_type');
            $table->integer('translatable_id');
            $table->string('key');
            $table->text('translation');
            $table->string('locale', 5);
            $table->timestamps();
        });
}