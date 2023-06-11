<?php

namespace Corals\Foundation\Shortcodes\Service;

class Widget implements WidgetContractInterface
{
    /**
     * @param      $key
     * @param null $arg
     *
     * @return mixed
     */
    public function get(string $key, $arg = null)
    {
        if (!isset(\Shortcode::widgets()[$key])) {
            return false;
        }
        $class = \Shortcode::widgets()[$key];
        if (!$class) {
            return "Widget $key Not Found !!";
        }
        $widget = new $class();
        return $widget->run($arg);
    }

    /**
     * Soother.
     */
    public function run()
    {
    }
}
