<?php

namespace Corals\Foundation\Facades;

use Illuminate\Support\Facades\Facade;
use Corals\Foundation\Classes\Hooks\Filters as HookFilters;

class Filters extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HookFilters::class;
    }
}
