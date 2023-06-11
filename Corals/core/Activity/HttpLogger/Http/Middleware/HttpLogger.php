<?php

namespace Corals\Activity\HttpLogger\Http\Middleware;

use Closure;
use Corals\Activity\HttpLogger\Contracts\LogProfile;
use Corals\Activity\HttpLogger\Contracts\LogWriter;
use Illuminate\Http\Request;

class HttpLogger
{
    protected $logProfile;
    protected $logWriter;

    public function __construct(LogProfile $logProfile, LogWriter $logWriter)
    {
        $this->logProfile = $logProfile;
        $this->logWriter = $logWriter;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!schemaHasTable('http_log')) {
            return $next($request);
        }

        $response = $next($request);

        if (!$this->logProfile->shouldLogRequest($request)) {
            return $response;
        }

        $this->logWriter->logRequest($request, $response);

        return $response;
    }
}
