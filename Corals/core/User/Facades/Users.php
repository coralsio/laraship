<?php

namespace Corals\User\Facades;

use Illuminate\Support\Facades\Facade;

class Users extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\User\Classes\Users::class;
    }
}