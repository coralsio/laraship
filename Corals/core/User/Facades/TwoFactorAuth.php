<?php

namespace Corals\User\Facades;

use Illuminate\Support\Facades\Facade;

class TwoFactorAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TwoFactorAuth';
    }
}
