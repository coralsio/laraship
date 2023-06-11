<?php

namespace Corals\Utility\Facades;

use Illuminate\Support\Facades\Facade;

class Utility extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Utility\Classes\Utility::class;
    }
}
