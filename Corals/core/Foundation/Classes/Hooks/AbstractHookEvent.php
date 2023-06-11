<?php

namespace Corals\Foundation\Classes\Hooks;

abstract class AbstractHookEvent
{
    /**
     * Stores the event listeners
     * @var array
     */
    protected $listeners = [];

    /**
     * Add a listener
     * @param string $hook Hook name
     * @param mixed $callback Function to execute
     * @param integer $priority Priority of the action
     */
    public function addListener($hook, $callback, $priority = 20)
    {
        $this->listeners[$hook][$priority] = compact('callback');
    }

    /**
     * Gets a sorted list of all listeners
     * @return array
     */
    public function getListeners()
    {
        /**
         * Sort by priority
         */
        foreach ($this->listeners as $key => &$listeners) {
            uksort($listeners, function ($param1, $param2) {
                return strnatcmp($param1, $param2);
            });
        }

        return $this->listeners;
    }

    /**
     * Get the function
     * @param $callback
     * @return \Closure|array|null|mixed
     * @author Tor Morten Jensen <tormorten@tormorten.no>
     */
    protected function getFunction($callback)
    {
        if (is_string($callback)) {
            if (strpos($callback, '@')) {
                $callback = explode('@', $callback);
                return [app('\\' . $callback[0]), $callback[1]];
            } else {
                return $callback;
            }
        } elseif ($callback instanceof \Closure) {
            return $callback;
        } elseif (is_array($callback) && sizeof($callback) > 1) {
            if (is_object($callback[0])) {
                return $callback;
            }
            return [app('\\' . $callback[0]), $callback[1]];
        }
        return null;
    }

    /**
     * Fires a new action
     * @param string $action Name of action
     * @param array $args Arguments passed to the action
     * @author Tor Morten Jensen <tormorten@tormorten.no>
     */
    abstract function dispatch($action, array $args);
}
