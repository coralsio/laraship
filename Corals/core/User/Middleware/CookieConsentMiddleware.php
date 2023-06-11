<?php

namespace Corals\User\Middleware;

use Closure;

class CookieConsentMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $consent = $_COOKIE['cookieconsent_status'] ?? "";

        if ($consent == "deny") {


            \Cookie::queue(\Cookie::forget('XSRF-TOKEN'));
            \Cookie::queue(\Cookie::forget('laravel_session'));

            $current_request = \Request::route()->getName();

            if (in_array($current_request, ['login', 'register'])) {
                flash(trans('User::messages.confirmation.accept_cookies'), 'error');
            }


        }

        return $next($request);
    }
}
