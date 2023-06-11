<?php

namespace Corals\Activity\HttpLogger\database\seeds;

use Illuminate\Database\Seeder;

class HttpLoggerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permissions')->insert([
            [
                'name' => 'Activity::http_log.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Activity::http_log.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ]);
    }
}
