<?php

namespace Corals\User\Providers;

use Corals\User\Models\User;
use Corals\User\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class UserObserverServiceProvider extends ServiceProvider
{
    /**
     * Register Observers
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }
}