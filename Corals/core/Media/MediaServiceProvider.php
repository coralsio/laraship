<?php

namespace Corals\Media;

use Corals\Media\Providers\MediaRouteServiceProvider;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    protected $defer = false;
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Media');

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Media');


    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/media.php', 'media');

        $this->app->register(MediaRouteServiceProvider::class);
    }
}
