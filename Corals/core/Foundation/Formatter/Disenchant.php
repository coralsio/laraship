<?php

namespace Corals\Foundation\Formatter;
/**
 * The **disenchant** trait can be used to disable dynamic property interactions
 * of objects.
 *
 * The magic methods defined here must be _public_, due to PHP aborting with
 * a fatal error if they are not. This means in effect that throwing of an error
 * is the only thing that is possible to disable dynamic property interactions.
 *
 * The methods must be _final_ too, in order to avoid that subclasses override
 * them, and consequently circumvent the restrictions laid out by the
 * superclass.
 */
trait Disenchant
{
    /** @throws \Error */
    final public function __get($_)
    {
        throw new \Error('Cannot get dynamic properties from immutable class ' . static::class);
    }

    /** @throws \Error */
    final public function __isset($_)
    {
        throw new \Error('Cannot check if dynamic properties are set on immutable class ' . static::class);
    }

    /** @throws \Error */
    final public function __set($_, $__)
    {
        throw new \Error('Cannot set dynamic properties on immutable class ' . static::class);
    }

    /** @throws \Error */
    final public function __unset($_)
    {
        throw new \Error('Cannot remove dynamic properties from immutable class ' . static::class);
    }
}
