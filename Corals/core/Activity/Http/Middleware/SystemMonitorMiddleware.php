<?php

namespace Corals\Activity\Http\Middleware;

use Corals\Activity\Facades\SystemMonitor;
use Closure;
use Illuminate\Http\Request;

class SystemMonitorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        SystemMonitor::monitor($request, $response);

        return $response;
    }
}