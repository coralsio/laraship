<?php

namespace Corals\Activity\HttpLogger\Classes;

use Corals\Activity\HttpLogger\Contracts\LogProfile;
use Illuminate\Http\Request;

class LogNonGetRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        if ($this->shouldExcludeURLPath($request)) {
            return false;
        }


        $methods = ['post', 'put', 'patch', 'delete'];

        $getIncluded = config('http_logger.get_included', false);

        if ($getIncluded) {
            $methods[] = 'get';
        }

        return in_array(strtolower($request->method()), $methods);
    }


    /**
     * @param Request $request
     * @return bool
     */
    protected function shouldExcludeURLPath(Request $request): bool
    {
        return $request->is(config('http_logger.exclude_url_paths'));
    }
}
