<?php

namespace Corals\User\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = 'dashboard';

        $this->title = 'User::module.dashboard.title';

        $this->title_singular = 'User::module.dashboard.title_singular';

        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = user()->roles()->first();

        if (!empty($role->dashboard_url) && !request()->is($role->dashboard_url)) {
            return redirect($role->dashboard_url);
        }

        $active_tab = 'dashboard';
        $active_tab = \Filters::do_filter('active_dashboard_tab', $active_tab, user());
        $dashboard_content = \Filters::do_filter('dashboard_content', '', $active_tab);
        return view('User::dashboard.user')->with(compact('dashboard_content'));
    }
}