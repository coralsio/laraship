<?php

namespace Corals\Foundation;


use Corals\Foundation\Facades\Actions;
use Corals\Foundation\Facades\Filters;
use Corals\Foundation\Shortcodes\Facades\Shortcode;
use Corals\Settings\Facades\Modules;
use Corals\Settings\Facades\Settings;
use Corals\Settings\SettingsServiceProvider;
use Corals\Theme\Facades\Theme;
use Corals\Theme\ThemeServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Hashids\Hashids;
use Illuminate\Support\Str;

class RecoveryServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $helpers = \File::glob(__DIR__ . '/Helpers/*.php');

        foreach ($helpers as $helper) {
            require_once $helper;
        }

        $this->app->register(FoundationServiceProvider::class);
        $this->app->register(SettingsServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);


        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Arr', Arr::class);
            $loader->alias('Str', Str::class);
            $loader->alias('Theme', Theme::class);
            $loader->alias('Actions', Actions::class);
            $loader->alias('Filters', Filters::class);
            $loader->alias('Settings', Settings::class);
            $loader->alias('Modules', Modules::class);
            $loader->alias('Shortcode', Shortcode::class);

        });

        // Bind 'hashids' shared component to the IoC container
        $this->app->singleton('hashids', function ($app) {

            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

            $salt = hash('sha256', 'Corals');

            return new Hashids($salt, 10, $alphabet);
        });


    }


    public function provides()
    {
        return ['hashids'];
    }
}
