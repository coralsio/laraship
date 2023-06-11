<?php

namespace Corals\Foundation\Http\Controllers;

use App\Http\Controllers\Controller;

class APIBaseController extends Controller
{
    protected $corals_middleware_except = [];
    protected $corals_middleware = ['auth:api'];

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->corals_middleware = \Filters::do_filter('corals_api_middleware', $this->corals_middleware, request());

        $this->middleware($this->corals_middleware, ['except' => $this->corals_middleware_except]);
    }
}
