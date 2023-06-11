<?php

use Symfony\Component\Debug\Exception\FatalThrowableError;


if (!function_exists('themes_path')) {

    function themes_path($filename = null)
    {
        return app()->make('corals.themes')->themes_path($filename);
    }
}

if (!function_exists('theme_url')) {

    function theme_url($url)
    {
        return app()->make('corals.themes')->url($url);
    }

}


if (!function_exists('render_blade_content')) {

    function render_blade_content($__php, $__data)
    {
        $__data['__env'] = app(\Illuminate\View\Factory::class);

        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            eval('?' . '>' . $__php);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new FatalThrowableError($e);
        }
        return ob_get_clean();
    }
}