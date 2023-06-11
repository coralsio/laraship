<?php

namespace Corals\Settings;

use Corals\Settings\Console\Commands\ModuleManager;
use Corals\Settings\Facades\CustomFields;
use Corals\Settings\Facades\Settings;
use Corals\Settings\Models\Country;
use Corals\Settings\Models\CustomFieldSetting;
use Corals\Settings\Models\ModelSetting;
use Corals\Settings\Models\Module;
use Corals\Settings\Models\Setting;
use Corals\Settings\Providers\SettingsAuthServiceProvider;
use Corals\Settings\Providers\SettingsRouteServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Settings');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Settings');

        // load
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Custom');

        //
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Module');

        //
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Cache');


        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerMorphMaps();
        $this->registerCustomFieldsModels();

    }

    protected function registerFacades()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('CustomFields', CustomFields::class);
        });

    }

    protected function registerMorphMaps()
    {
        Relation::morphMap([
            'Country' => Country::class,
            'CustomFieldSetting' => CustomFieldSetting::class,
            'ModelSetting' => ModelSetting::class,
            'Module' => Module::class,
            'Setting' => Setting::class
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/settings.php', 'settings');
        $this->app->register(SettingsAuthServiceProvider::class);
        $this->app->register(SettingsRouteServiceProvider::class);
        $this->registerCommand();
        $this->registerFacades();
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(Setting::class);
    }

    protected function registerCommand()
    {
        $this->commands(ModuleManager::class);
    }
}
