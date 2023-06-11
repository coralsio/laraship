<?php

namespace Corals\User\Listeners;

use Illuminate\Auth\Events\Registered;

class UserEventSubscriber
{
    /**
     * @var string
     */
    protected $authLogName = 'auth';

    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        $user = $event->user;

        activity($this->authLogName)
            ->causedBy($user)
            ->withProperties(['ip' => request()->ip()])
            ->log("{$user->full_name} logged In");
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
        $user = $event->user;

        activity($this->authLogName)
            ->causedBy($user)
            ->withProperties(['ip' => request()->ip()])
            ->log("{$user->full_name} logged Out");
    }

    /**
     * Handle user registration events.
     */
    public function onUserRegistered($event)
    {
        $user = $event->user;

        activity($this->authLogName)
            ->causedBy($user)
            ->withProperties(['ip' => request()->ip()])
            ->log("{$user->full_name} registered");

        event('notifications.user.registered', ['user' => $user]);
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'Corals\User\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'Corals\User\Listeners\UserEventSubscriber@onUserLogout'
        );

        $events->listen(Registered::class,
            'Corals\User\Listeners\UserEventSubscriber@onUserRegistered');
    }
}
