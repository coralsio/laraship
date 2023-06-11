<?php

namespace Corals;

use Corals\Foundation\RecoveryServiceProvider;
use Corals\Modules\ModulesServiceProvider;
use Corals\Foundation\FoundationServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class CoralServiceProvider extends ServiceProvider
{
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

        //Load Minimal modules and classes when calling module manager
        if (app()->runningInConsole() && (Arr::get(request()->server(), 'argv.1') === 'corals:modules')) {

            $this->app->register(RecoveryServiceProvider::class);

        } else {
            $this->app->register(FoundationServiceProvider::class);

            //load modules last thing
            if (class_exists(ModulesServiceProvider::class)) {
                $this->app->register(ModulesServiceProvider::class);
            }
        }

    }
}
