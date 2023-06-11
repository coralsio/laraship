<?php

namespace Corals\Foundation\Classes\Hooks;

class Filters extends AbstractHookEvent
{
    /**
     * @param string $action
     * @param array $args
     * @return mixed|string
     */
    public function dispatch($action, array $args)
    {
        $value = isset($args[0]) ? $args[0] : '';

        if ($this->getListeners()) {
            foreach ($this->getListeners() as $hook => $listeners) {
                if ($hook === $action) {
                    foreach ($listeners as $arguments) {
                        $args[0] = $value;
                        $value = call_user_func_array($this->getFunction($arguments['callback']), $args);
                    }
                }
            }
        }

        return $value;
    }

    /**
     * @param string $hook
     * @param \Closure|string|array|callable $callback
     * @param int $priority
     */
    function add_filter($hook, $callback, $priority = 20)
    {
        self::addListener($hook, $callback, $priority);
    }


    /**
     * Do action then return value
     * @param string $hookName
     * @param array ...$args
     * @return mixed
     */
    function do_filter($hookName, ...$args)
    {
        return self::dispatch($hookName, $args);
    }

    /**
     * @param null $name
     * @return array|null
     */
    function get_filters($name = null)
    {
        $listeners = self::getListeners();

        if (empty($name)) {
            return $listeners;
        }
        return \Arr::get($listeners, $name);
    }
}
