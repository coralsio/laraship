<?php

namespace Corals\Menu;

use Corals\Menu\Models\Menu;
use Corals\Menu\Providers\MenuAuthServiceProvider;
use Corals\Menu\Providers\MenuRouteServiceProvider;
use Corals\Settings\Facades\Settings;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Menu');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Menu');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerMorphMaps();
        $this->registerCustomFieldsModels();

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/menu.php', 'menu');

        $this->app->register(MenuAuthServiceProvider::class);
        $this->app->register(MenuRouteServiceProvider::class);
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(Menu::class);
    }

    protected function registerMorphMaps()
    {
        Relation::morphMap([
            'Menu' => Menu::class,
        ]);
    }
}
