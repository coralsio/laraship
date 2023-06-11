<?php

namespace Corals\Foundation\Http\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public $resource_url = '';
    public $resource_model = null;
    public $title = '';
    public $title_singular = '';
    protected $corals_middleware_except = [];
    protected $corals_middleware = ['auth'];

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->corals_middleware = \Filters::do_filter('corals_middleware', $this->corals_middleware, request());

        $this->middleware($this->corals_middleware, ['except' => $this->corals_middleware_except]);

        $this->middleware(function ($request, $next) {
            $this->setTheme();

            $this->setViewSharedData();

            return $next($request);
        });
    }

    public function setTheme()
    {
        \Theme::set($this->getDefaultAdminTheme());
    }

    protected function getDefaultAdminTheme()
    {
        $default_admin_theme = \Settings::get('active_admin_theme', config('themes.corals_admin'));

        if (session()->has('dashboard_theme')) {
            $default_admin_theme = session('dashboard_theme');

            $theme = \Theme::find($default_admin_theme);

            $this->loadThemeTranslations($theme);
        }

        return $default_admin_theme;
    }

    protected function loadThemeTranslations($theme)
    {
        $path = $theme->getViewPaths()[0] ?? null;

        if ($path) {
            $path .= '/lang';
            app()['translator']->addNamespace($theme->name, $path);
        }

        if (!is_null($theme->parent)) {
            $this->loadThemeTranslations($theme->parent);
        }
    }

    /**
     * set variables shared with all controller views
     * @param array $variables
     */
    protected function setViewSharedData($variables = [])
    {
        $this->title_singular = trans(\Arr::get($variables, 'title_singular', $this->title_singular));
        $variables['title_singular'] = $this->title_singular;

        $this->title = trans(\Arr::get($variables, 'title', $this->title));
        $variables['title'] = $this->title;

        $this->resource_url = \Arr::get($variables, 'resource_url', $this->resource_url);
        $variables['resource_url'] = $this->resource_url;

        $this->resource_model = \Arr::get($variables, 'resource_model', $this->resource_model);
        $variables['resourceModel'] = $this->resource_model;

        view()->share($variables);
    }
}
