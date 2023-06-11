<?php

namespace Corals\Foundation\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class ModelActionsHandler extends IlluminateFacade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Foundation\Classes\ModelActionsHandler::class;
    }
}
