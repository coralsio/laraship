<?php

namespace Corals\Activity\HttpLogger\Contracts;

use Illuminate\Http\Request;

interface LogProfile
{
    public function shouldLogRequest(Request $request): bool;
}
