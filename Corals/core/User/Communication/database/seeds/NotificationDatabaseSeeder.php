<?php

namespace Corals\User\Communication\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationDatabaseSeeder extends Seeder
{
    public function run()
    {
//        $this->call(NotificationMenuTableSeeder::class);
        $this->call(NotificationPermissionsTableSeeder::class);
        $this->call(NotificationTemplatesTableSeeder::class);
    }
}
