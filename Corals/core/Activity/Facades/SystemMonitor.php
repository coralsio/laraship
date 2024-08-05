<?php


namespace Corals\Activity\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Corals\Activity\Classes\SystemMonitor as SystemMonitorClass;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SystemMonitor
 * @method static void configure()
 * @method static void monitor(Request $request, Response $response)
 */
class SystemMonitor extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SystemMonitorClass::class;
    }
}