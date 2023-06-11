<?php

namespace Corals\Foundation\Http\Middleware;

use Carbon\Carbon;
use Closure;

class SetLocale
{
    /**
     * This function checks if language to set is an allowed lang of config.
     *
     * @param string $locale
     **/
    private function setLocale($locale)
    {
        // Check if is allowed and set default locale if not
        if (!\Language::allowed($locale)) {
            $locale = config('app.locale');
        }

        // Set app language
        \App::setLocale($locale);

        // Set carbon language
        if (config('language.carbon')) {
            // Carbon uses only language code
            if (config('language.mode.code') == 'long') {
                $locale = explode('-', $locale)[0];
            }

            Carbon::setLocale($locale);
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (user() && config('language.user_level_enabled', false) && user()->locale) {
            $locale = user()->locale;
        } elseif ($request->has('lang')) {
            $locale = $request->get('lang');
            \Language::setLanguage($locale);
        } else {
            $locale = \Language::getCurrentLanguage();
        }

        $this->setLocale($locale);

        return $next($request);
    }
}
