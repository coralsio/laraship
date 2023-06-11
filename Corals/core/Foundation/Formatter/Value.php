<?php

namespace Corals\Foundation\Formatter;

/**
 * Inspect PHP values.
 */
final class Value
{
    /** @see https://php.net/types.array */
    const TYPE_ARRAY = 'array';

    /** @see https://php.net/types.boolean */
    const TYPE_BOOL = 'boolean';

    /** @see https://php.net/types.callable */
    const TYPE_CALLABLE = 'callable';

    /** @see https://php.net/types.resource */
    const TYPE_CLOSED_RESOURCE = 'closed resource';

    /** @see https://php.net/types.float */
    const TYPE_FLOAT = 'float';

    /** @see https://php.net/types.integer */
    const TYPE_INT = 'integer';

    /** @see https://php.net/types.iterable */
    const TYPE_ITERABLE = 'iterable';

    /** @see https://php.net/types.null */
    const TYPE_NULL = 'null';

    /** @see https://php.net/types.object */
    const TYPE_OBJECT = 'object';

    /** @see https://php.net/types.resource */
    const TYPE_RESOURCE = 'resource';

    /** @see https://php.net/types.string */
    const TYPE_STRING = 'string';

    /** Final abstract class. */
    private function __construct()
    {
    }

    /**
     * Get a human-readable description of the given value’s type as used by
     * PHP itself in (error) messages.
     *
     * PHP uses a highly inconsistent naming when it comes to types. This is not
     * only true for functions like {@see \gettype} but also for the messages
     * that are produced by PHP; not to mention the confusion in the C code.
     *
     * The type names used in this method are consistent with the ones used in
     * PHP 7’s {@see \TypeError} messages which I believe are the ones that
     * most developers will see in the future and get most used to. This also
     * means that the full class name is returned for objects instead of simply
     * _object_.
     *
     * Another special edge case which this method handles properly is the case
     * of closed resources which are reported as _unknown type_ by
     * {@see \gettype} which might cast some confusion in certain situations.
     * This method will return _closed resource_ in such situations.
     *
     * Any unknown type will result in the string _unknown_, however, it should
     * not be possible that this happens and a bug should be filed against PHP
     * if this is encountered.
     *
     * @param mixed $value
     *     Value to inspect.
     * @return string
     *     Human-readable name for the type of the given value.
     * @see \get_class()
     * @see \gettype()
     */
    public static function getType($value)
    {
        if ($value === \null) {
            return static::TYPE_NULL;
        }

        if (\is_array($value)) {
            return static::TYPE_ARRAY;
        }

        if (\is_bool($value)) {
            return static::TYPE_BOOL;
        }

        if (\is_float($value)) {
            return static::TYPE_FLOAT;
        }

        if (\is_int($value)) {
            return static::TYPE_INT;
        }

        if (\is_object($value)) {
            return \get_class($value);
        }

        if (\is_string($value)) {
            return static::TYPE_STRING;
        }

        if (\is_resource($value)) {
            return static::TYPE_RESOURCE;
        }

        if (@\get_resource_type($value) === 'Unknown') {
            return static::TYPE_CLOSED_RESOURCE;
        }

        // @codeCoverageIgnoreStart
        // It should not be possible to reach this point since the above covers
        // all known PHP types, hence, there is no way to test this branch.
        return 'unknown';
        // @codeCoverageIgnoreEnd
    }
}
