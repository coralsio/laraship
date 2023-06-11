<?php

namespace Corals\User\Communication;

use Corals\User\Communication\Models\Notification;
use Corals\User\Communication\Models\NotificationTemplate;
use Corals\User\Communication\Providers\NotificationAuthServiceProvider;
use Corals\User\Communication\Providers\NotificationEventServiceProvider;
use Corals\User\Communication\Providers\NotificationObserverServiceProvider;
use Corals\User\Communication\Providers\NotificationRouteServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class CommunicationServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Notification');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Notification');

        $this->registerMorphMaps();

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        \JavaScript::put([
            'laravel_echo_domain' => config('notification.laravel_echo_domain')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/notification.php', 'notification');

        $this->app->register(NotificationAuthServiceProvider::class);
        $this->app->register(NotificationRouteServiceProvider::class);
        $this->app->register(NotificationObserverServiceProvider::class);
        $this->app->register(NotificationEventServiceProvider::class);
    }

    protected function registerMorphMaps()
    {
        Relation::morphMap([
            'Notification' => Notification::class,
            'NotificationTemplate' => NotificationTemplate::class
        ]);
    }
}
