<?php

namespace Corals\Foundation\Classes;

class Language
{

    /**
     * Language constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get single flag view.
     *
     * @param string $code
     *
     * @return mixed
     **/
    public function flag($code = 'default')
    {
        if ($code == 'default') {
            $code = app()->getLocale();
        }

        $name = self::getName($code);
        $country_code = self::country($code);

        return view('Corals::language.flag', compact('country_code', 'code', 'name'));
    }

    /**
     * Get country code based on locale.
     *
     * @param string $locale
     *
     * @return mixed
     **/
    public function country($locale = 'default')
    {
        if ($locale == 'default') {
            $locale = app()->getLocale();
        }

        if (config('language.mode.code', 'short') == 'short') {
            $code = strtolower(substr(self::getLongCode($locale), 3));
        } else {
            $code = strtolower(substr($locale, 3));
        }

        return $code;
    }

    /**
     * Get all flags view.
     * @param $ul_class
     * @param $li_class
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function flags($ul_class = null, $li_class = null)
    {
        return view('Corals::language.flags')->with(compact('ul_class', 'li_class'));
    }

    /**
     * Return true if $code is an allowed lang.
     * Get all allowed languages.
     *
     * @param string $locale
     *
     * @return bool|array
     **/
    public function allowed($locale = null)
    {
        if ($locale) {
            return in_array($locale, array_keys(self::allowed()));
        }

        $allowed = array_keys(\Settings::get('supported_languages', []));

        if (empty($allowed)) {
            $allowed = [config('app.locale')];
        }

        if (!empty($allowed)) {
            return self::names(array_merge($allowed, [config('app.locale')]));
        } else {
            return self::names([config('app.locale')]);
        }
    }

    /**
     * Add names to an array of language codes as [$code => $language].
     *
     * @param array $codes
     * @param boolean $english
     * @return array
     **/
    public function names($codes, $english = false)
    {
        // Get mode
        $mode = config('language.mode');

        // Get languages from config
        $languages = config('language.all');

        $array = [];

        // Generate an array with $code as key and $code language as value
        foreach ($codes as $code) {
            $lang_name = 'Unknown';

            foreach ($languages as $language) {
                if ($language[$mode['code']] == $code) {
                    if ($english) {
                        $lang_name = $language['english'];
                    } else {
                        $lang_name = $language[$mode['name']];
                    }
                }
            }

            $array[$code] = $lang_name;
        }

        return $array;
    }

    /**
     * Add names to an array of language codes as [$language => $code].
     *
     * @param array $langs
     *
     * @return array
     **/
    public function codes($langs)
    {
        // Get mode
        $mode = config('language.mode');

        // Get languages from config
        $languages = config('language.all');

        $array = [];

        // Generate an array with $lang as key and $lang code as value
        foreach ($langs as $lang) {
            $lang_code = 'unk';

            foreach ($languages as $language) {
                if ($language[$mode['name']] == $lang) {
                    $lang_code = $language[$mode['code']];
                }
            }

            $array[$lang] = $lang_code;
        }

        return $array;
    }

    /**
     * Returns the language code.
     *
     * @param string $name
     *
     * @return string
     **/
    public function getCode($name = 'default')
    {
        if ($name == 'default') {
            $name = self::getName();
        }

        return self::codes([$name])[$name];
    }

    /**
     * Returns the language long code.
     *
     * @param string $short
     *
     * @return string
     **/
    public function getLongCode($short = 'default')
    {
        if ($short == 'default') {
            $short = app()->getLocale();
        }

        $long = 'en-GB';

        // Get languages from config
        $languages = config('language.all');

        foreach ($languages as $language) {
            if ($language['short'] != $short) {
                continue;
            }

            $long = $language['long'];
        }

        return $long;
    }

    /**
     * Returns the language short code.
     *
     * @param string $long
     *
     * @return string
     **/
    public function getShortCode($long = 'default')
    {
        if ($long == 'default') {
            $long = app()->getLocale();
        }

        $short = 'en';

        // Get languages from config
        $languages = config('language.all');

        foreach ($languages as $language) {
            if ($language['long'] != $long) {
                continue;
            }

            $short = $language['short'];
        }

        return $short;
    }

    /**
     * Returns the language name.
     *
     * @param string $code
     *
     * @return string
     **/
    public function getName($code = 'default')
    {
        if ($code == 'default') {
            $code = app()->getLocale();
        }

        return self::names([$code])[$code];
    }

    /**
     * Returns the language name.
     *
     * @param string $code
     *
     * @return string
     **/
    public function getNameEnglish($code = 'default')
    {
        if ($code == 'default') {
            $code = app()->getLocale();
        }

        return self::names([$code], true)[$code];
    }

    public function getLocaleUrl($code = 'default')
    {
        if ($code == 'default') {
            $code = app()->getLocale();
        }

        return url('set-locale/' . $code);
    }

    /**
     * Sets the language flag and returns the cookie to be created on the redirect
     * @param $locale
     * @return mixed | null
     */
    public function setLanguage($locale)
    {
        // Check if is allowed and set default locale if not
        if (!$this->allowed($locale)) {
            $locale = config('app.locale');
        }

        if (user() && config('language.user_level_enabled', false)) {
            return user()->setAttribute('locale', $locale)->save();
        }

        $store = config('language.store', 'cookie');

        $store_key = config('language.store_key', 'locale');

        if ($store == 'cookie') {
            return \Cookie::queue($store_key, $locale);
        }

        session()->put($store_key, $locale);

        return cookie('dummy-cookie', FALSE, 1); //just for cleaner code in the controller
    }

    /**
     * Returns the current language set in the session/cookie
     * @return string
     */
    public function getCurrentLanguage()
    {
        $store = config('language.store', 'cookie');

        $store_key = config('language.store_key', 'locale');

        $fallback = config('app.locale');

        if ($store == 'session') {
            return session($store_key, $fallback);
        }

        return request()->cookie($store_key) ?: $fallback;
    }

    /**
     * Returns current locale direction
     *
     * @return string current locale direction
     */
    public function getDirection()
    {

        switch ($this->getCode()) {
            // Other (historic) RTL scripts exist, but this list contains the only ones in current use.
            case 'ar':
            case 'he':
            case 'mo':
                return 'rtl';
            default:
                return 'ltr';
        }
    }

    /**
     * Returns current locale direction
     *
     * @return string current locale direction
     */
    public function isRTL()
    {

        return $this->getDirection() == "rtl";
    }
}
