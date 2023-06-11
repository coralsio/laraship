<?php

namespace Corals\User\Communication\Providers;

use Corals\User\Communication\Models\NotificationTemplate;
use Corals\User\Communication\Observers\NotificationObserver;
use Corals\User\Communication\Observers\NotificationTemplateObserver;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\ServiceProvider;

class NotificationObserverServiceProvider extends ServiceProvider
{
    /**
     * Register Observers
     */
    public function boot()
    {
        NotificationTemplate::observe(NotificationTemplateObserver::class);
        DatabaseNotification::observe(NotificationObserver::class);
    }
}
