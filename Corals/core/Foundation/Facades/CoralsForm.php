<?php

namespace Corals\Foundation\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class CoralsForm extends IlluminateFacade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Foundation\Classes\CoralsForm::class;
    }
}
