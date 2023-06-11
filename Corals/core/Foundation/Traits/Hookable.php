<?php

namespace Corals\Foundation\Traits;

use Closure;
use ReflectionClass;
use ReflectionMethod;

trait Hookable
{
    /**
     * @param array $attributes
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    protected static $macros = [];

    public static function bootHookable()
    {
        static::updating(function ($item) {
            $class_name = strtolower(class_basename(get_class($item)));

            \Actions::do_action('pre_update', $item);
            \Actions::do_action('pre_update_' . $class_name, $item);

            $item = \Filters::do_filter('pre_update_' . $class_name, $item);
        });

        static::updated(function ($item) {
            $class_name = strtolower(class_basename(get_class($item)));
            \Actions::do_action('post_update', $item);
            \Actions::do_action('post_update_' . $class_name, $item);
        });

        static::deleting(function ($item) {
            $class_name = strtolower(class_basename(get_class($item)));
            \Actions::do_action('pre_delete', $item);
            \Actions::do_action('pre_delete_' . $class_name, $item);
        });

        static::deleted(function ($item) {
            $class_name = strtolower(class_basename(get_class($item)));
            \Actions::do_action('post_delete', $item);
            \Actions::do_action('post_delete_' . $class_name, $item);
        });

        static::creating(function ($item) {
            $class_name = strtolower(class_basename(get_class($item)));
            \Actions::do_action('pre_create', $item);
            \Actions::do_action('pre_create_' . $class_name, $item);
        });

        static::deleted(function ($item) {
            $class_name = strtolower(class_basename(get_class($item)));
            \Actions::do_action('post_create', $item);
            \Actions::do_action('post_create_' . $class_name, $item);
        });
    }

    public static function __callStatic($method, $parameters)
    {
        if (!static::hasMacro($method)) {
            return parent::__callStatic($method, $parameters);
        }

        if (static::$macros[$method] instanceof Closure) {
            return call_user_func_array(Closure::bind(static::$macros[$method], null, static::class), $parameters);
        }

        return call_user_func_array(static::$macros[$method], $parameters);
    }

    public function __call($method, $parameters)
    {

        if (!static::hasMacro($method)) {
            return parent::__call($method, $parameters);
        }

        $macro = static::$macros[$method];

        if ($macro instanceof Closure) {

            return call_user_func_array($macro->bindTo($this, static::class), $parameters);
        }

        return call_user_func_array($macro, $parameters);
    }


    /**
     * Register a custom macro.
     *
     * @param  string $name
     * @param  object|callable $macro
     */
    public static function macro(string $name, $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {


        if (!static::hasMacro($key)) {

            return parent::__get($key);
        }

        $macro = static::$macros[$key];

        if ($macro instanceof Closure) {
            return call_user_func_array($macro->bindTo($this, static::class), [["getData" => true]]);
        }

        return call_user_func_array($macro, []);
    }

    public static function hasMacro(string $name): bool
    {
        return isset(static::$macros[$name]);
    }

    /**
     *  Mix another object into the class.
     * @param $mixin
     * @throws \ReflectionException
     */
    public static function mixin($mixin)
    {
        $methods = (new ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            $method->setAccessible(true);

            static::macro($method->name, $method->invoke($mixin));
        }
    }
}
