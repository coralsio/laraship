<?php

namespace Corals\Utility\Providers;

use Corals\Foundation\Providers\BaseUninstallModuleServiceProvider;
use Corals\Utility\database\seeds\UtilityDatabaseSeeder;

class UninstallModuleServiceProvider extends BaseUninstallModuleServiceProvider
{
    protected $migrations = [
    ];

    protected function providerBooted()
    {
        $this->dropSchema();

        $utilityDatabaseSeeder = new UtilityDatabaseSeeder();

        $utilityDatabaseSeeder->rollback();
    }
}
