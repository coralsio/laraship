<?php

namespace Corals\Foundation\Facades;

use Illuminate\Support\Facades\Facade;
use Corals\Foundation\Classes\Hooks\Actions as HookActions;

class Actions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HookActions::class;
    }
}
