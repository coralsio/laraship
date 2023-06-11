<?php

namespace Corals\User\Facades;

use Illuminate\Support\Facades\Facade;

class Roles extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\User\Classes\Roles::class;
    }
}