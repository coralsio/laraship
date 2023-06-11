<?php

\Illuminate\Support\Facades\Schema::table('utility_categories', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->foreign('parent_id')->references('id')
        ->on('utility_categories')->onDelete('cascade')->onUpdate('cascade');
});