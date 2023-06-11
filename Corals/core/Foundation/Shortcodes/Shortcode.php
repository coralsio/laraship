<?php

namespace Corals\Foundation\Shortcodes;

use Illuminate\Support\Facades\Blade;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class Shortcode
{


    protected $tags = array();
    protected $widgets = array();

    /**
     * Add shortcode tag and their callaback.
     *
     * @param $tag
     * @param $callback
     * @return bool
     */
    public function add($tag, $callback)
    {
        if (!$this->exists($tag)) {
            Blade::directive($tag, $callback);
            $this->tags[$tag] = $callback;

            return true;
        }


        return false;
    }


    /**
     * compile shortcode tag and their callaback.
     * @param $tag
     * @param $arguments
     * @return string
     * @throws \Exception
     */
    public function compile($tag, $arguments)
    {
        $result = (call_user_func($this->tags[$tag], $arguments));
        $__data['__env'] = app(\Illuminate\View\Factory::class);

        extract($__data);
        $obLevel = ob_get_level();
        ob_start();

        try {
            eval('?' . '>' . $result);
        } catch (\Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new FatalThrowableError($e);
        }

        return ob_get_clean();
    }

    public function addWidget($widget, $className)
    {
        $this->widgets[$widget] = $className;
    }

    /**
     * Whether a registered shortcode tag exists.
     *
     * @param $tag
     * @return bool
     */
    protected function exists($tag)
    {
        return array_key_exists($tag, $this->tags);
    }

    public function tags()
    {
        return $this->tags;
    }

    public function widgets()
    {
        return $this->widgets;
    }
}
