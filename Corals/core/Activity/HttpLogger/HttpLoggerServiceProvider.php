<?php

namespace Corals\Activity\HttpLogger;

use Corals\Activity\HttpLogger\Contracts\LogProfile;
use Corals\Activity\HttpLogger\Contracts\LogWriter;
use Corals\Activity\HttpLogger\Http\Middleware\HttpLogger;
use Corals\Activity\HttpLogger\Models\HttpLog;
use Corals\Activity\HttpLogger\Providers\HttpLoggerAuthServiceProvider;
use Corals\Activity\HttpLogger\Providers\HttpLoggerRouteServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class HttpLoggerServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'HttpLogger');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'HttpLogger');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerMorphMaps();

        $this->app->singleton(LogProfile::class, config('http_logger.log_profile'));
        $this->app->singleton(LogWriter::class, config('http_logger.log_writer'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/http_logger.php', 'http_logger');

        if (config('http_logger.is_enabled')) {
            $this->app['router']->pushMiddlewareToGroup('web', HttpLogger::class);
            $this->app['router']->pushMiddlewareToGroup('api', HttpLogger::class);
        }

        $this->app->register(HttpLoggerRouteServiceProvider::class);

        $this->app->register(HttpLoggerAuthServiceProvider::class);
    }

    protected function registerMorphMaps()
    {
        Relation::morphMap([
            'HttpLog' => HttpLog::class
        ]);
    }
}
