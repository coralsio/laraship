<?php

namespace Corals\User\Communication\Providers;

use Corals\User\Communication\Listeners\NotificationEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class NotificationEventServiceProvider extends ServiceProvider
{


    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];


    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        NotificationEventSubscriber::class,
    ];


}
