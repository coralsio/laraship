<?php

namespace Corals\Settings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method  static getSettingsByCategory($category, $isPublic = false, $asObjects = true)
 */
class Settings extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Settings\Classes\Settings::class;
    }
}