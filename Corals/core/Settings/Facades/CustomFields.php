<?php

namespace Corals\Settings\Facades;

use Illuminate\Support\Facades\Facade;

class CustomFields extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Settings\Classes\CustomFields::class;
    }
}