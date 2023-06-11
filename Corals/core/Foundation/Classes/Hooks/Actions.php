<?php

namespace Corals\Foundation\Classes\Hooks;

class Actions extends AbstractHookEvent
{
    /**
     * @param string $action
     * @param array $args
     */
    public function dispatch($action, array $args)
    {
        if ($this->getListeners()) {
            foreach ($this->getListeners() as $hook => $listeners) {
                if ($hook === $action) {
                    foreach ($listeners as $arguments) {
                        call_user_func_array($this->getFunction($arguments['callback']), $args);
                    }
                }
            }
        }
    }

    /**
     * @param string $hook
     * @param \Closure|string|array|callable $callback
     * @param int $priority
     */
    function add_action($hook, $callback, $priority = 20)
    {
        self::addListener($hook, $callback, $priority);
    }


    /**
     * Do actions
     * @param string $hookName
     * @param array ...$args
     */
    function do_action($hookName, ...$args)
    {
        self::dispatch($hookName, $args);
    }

    /**
     * @param null $name
     * @return array|null
     */
    function get_actions($name = null)
    {
        $listeners = self::getListeners();

        if (empty($name)) {
            return $listeners;
        }
        return \Arr::get($listeners, $name);
    }

}
