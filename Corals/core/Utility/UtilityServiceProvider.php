<?php

namespace Corals\Utility;

use Corals\Foundation\Providers\BasePackageServiceProvider;
use Corals\Utility\Facades\Utility;
use Corals\Utility\Providers\UtilityAuthServiceProvider;
use Corals\Utility\Providers\UtilityObserverServiceProvider;
use Corals\Utility\Providers\UtilityRouteServiceProvider;
use Illuminate\Foundation\AliasLoader;

class UtilityServiceProvider extends BasePackageServiceProvider
{
    protected $defer = true;

    protected $packageCode = 'corals-utility';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */

    public function bootPackage()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Utility');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Utility');

        // Load migrations
//        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerMorphMaps();
        $this->registerCustomFieldsModels();

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerPackage()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/utility.php', 'utility');

        $this->app->register(UtilityRouteServiceProvider::class);
        $this->app->register(UtilityAuthServiceProvider::class);
        $this->app->register(UtilityObserverServiceProvider::class);

        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Utility', Utility::class);
        });

    }

    protected function registerMorphMaps()
    {
    }

    protected function registerCustomFieldsModels()
    {
    }

    public function registerModulesPackages()
    {
    }
}
