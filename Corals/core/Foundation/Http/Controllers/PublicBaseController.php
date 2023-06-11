<?php

namespace Corals\Foundation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicBaseController extends BaseController
{
    /**
     * PublicBaseController constructor.
     */
    public function __construct()
    {
        $this->corals_middleware = [];
        $this->corals_middleware_except = [];
        parent::__construct();
    }

    public function setTheme()
    {
        \Theme::set(\Settings::get('active_frontend_theme', config('themes.corals_frontend')));
    }

    public function welcome(Request $request)
    {
        return view('welcome');
    }
}