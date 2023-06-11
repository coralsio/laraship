<?php

namespace Corals\Utility\Providers;

use Corals\Foundation\Providers\BaseInstallModuleServiceProvider;
use Corals\Utility\database\seeds\UtilityDatabaseSeeder;

class InstallModuleServiceProvider extends BaseInstallModuleServiceProvider
{
    protected $module_public_path = __DIR__ . '/../public';

    protected $migrations = [
    ];

    protected function providerBooted()
    {
        $this->createSchema();

        $utilityDatabaseSeeder = new UtilityDatabaseSeeder();

        $utilityDatabaseSeeder->run();
    }
}
