<?php

namespace Corals\Foundation\Http\Controllers;

use Corals\User\Models\Role;

class AuthBaseController extends BaseController
{


    /**
     * AuthBaseController constructor.
     */
    public function __construct()
    {
        if (!\Settings::get('confirm_user_registration_email', false) && session()->has('confirmation_user_id')) {
            session()->forget('confirmation_user_id');
        }

        parent::__construct();
    }

    public function setTheme()
    {
        $auth_theme = $this->getDefaultAdminTheme();

        $active_frontend_theme = \Settings::get('active_frontend_theme');
        $useFrontendTheme = false;

        if (request()->is([
                'password/*',
                '*/login',
                '*/register',
                'login',
                'register'
            ]) && \Theme::theme_view_exists($active_frontend_theme,
                'auth/login')) {
            $useFrontendTheme = true;
        } elseif (request()->is(['profile']) && \Theme::theme_view_exists($active_frontend_theme, 'auth/profile')) {
            $useFrontendTheme = true;
        }

        if ($useFrontendTheme) {
            $auth_theme = $active_frontend_theme;
            $auth_theme = \Filters::do_filter('auth_theme', $auth_theme);
        }

        \Theme::set($auth_theme);
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return $this->redirectTo();
    }

    public function redirectTo()
    {
        $redirect_to_default = 'dashboard';
        $dashboard_url = \Filters::do_filter('dashboard_url', $redirect_to_default);


        $redirect_to = "";
        if (user()) {
            $role = user()->roles()->first();
            if (!empty($role->redirect_url)) {
                $redirect_to = $role->redirect_url;
            } elseif (!empty($role->dashboard_url)) {
                $redirect_to = $role->dashboard_url;
            }
        }

        $redirect_to = \Filters::do_filter('auth_redirect_to', $redirect_to);

        if (!$redirect_to) {
            $redirect_to = $dashboard_url;
        }


        return $redirect_to;
    }

    public function assignDefaultRoles($user, $roleName = null)
    {
        $available_registration_roles = \Settings::get('available_registration_roles', []);

        $default_role_name = \Settings::get('default_user_role', 'member');

        if ($roleName && in_array($roleName, array_keys($available_registration_roles))) {
            $role_exists = Role::where('name', '=', $roleName)
                ->count();

            if (!$role_exists) {
                $roleName = $default_role_name;
            }
        } else {
            $roleName = $default_role_name;
        }

        $user->assignRole($roleName);
    }
}
