<?php


use Illuminate\Support\Facades\DB;

DB::statement("ALTER TABLE model_settings modify column cast_type ENUM('string', 'integer', 'boolean', 'float', 'array') default 'string'");
