<?php

namespace Corals\Activity\HttpLogger\Providers;


use Corals\Activity\HttpLogger\Models\HttpLog;
use Corals\Activity\HttpLogger\Policies\HttpLogPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class HttpLoggerAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        HttpLog::class => HttpLogPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}