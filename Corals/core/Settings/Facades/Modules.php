<?php

namespace Corals\Settings\Facades;

use Illuminate\Support\Facades\Facade;

class Modules extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Settings\Classes\Modules::class;
    }
}