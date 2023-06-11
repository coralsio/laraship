<?php

namespace Corals\Activity\HttpLogger\Contracts;

use Illuminate\Http\Request;

interface LogWriter
{
    /**
     * @param Request $request
     * @param $response
     * @return mixed
     */
    public function logRequest(Request $request,$response);
}
