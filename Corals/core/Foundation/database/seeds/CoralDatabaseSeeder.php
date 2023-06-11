<?php

namespace Corals\Foundation\database\seeds;

use Corals\Activity\database\seeds\ActivitiesDatabaseSeeder;
use Corals\Menu\database\seeds\MenusTableSeeder;
use Corals\Settings\database\seeds\SettingsTableSeeder;
use Corals\Settings\Facades\Modules;
use Corals\User\database\seeds\UsersDatabaseSeeder;
use Illuminate\Database\Seeder;

class CoralDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ActivitiesDatabaseSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(UsersDatabaseSeeder::class);
        $this->call(MenusTableSeeder::class);

        Modules::grantSuperuserRoleFullAccess();
    }
}
