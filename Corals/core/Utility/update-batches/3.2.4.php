<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('utility_schedules')) {
    DB::statement("ALTER TABLE `utility_schedules` MODIFY COLUMN `user_id` int(10) UNSIGNED NULL AFTER `id`;");
}
