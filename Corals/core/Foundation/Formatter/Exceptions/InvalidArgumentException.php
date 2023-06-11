<?php


declare(strict_types=1);

namespace Corals\Foundation\Formatter\Exceptions;

use Corals\Foundation\Formatter\Value;

/**
 * Formatter exception if an argument is given that cannot be formatted in a
 * meaningful way.
 */
class InvalidArgumentException extends \InvalidArgumentException
{
    public static function new($argument): self
    {
        return new self('Cannot format ' . Value::getType($argument));
    }
}
