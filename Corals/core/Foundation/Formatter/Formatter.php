<?php


declare(strict_types=1);

namespace Corals\Foundation\Formatter;

use Corals\Foundation\Formatter\Exceptions\InvalidArgumentException;
use Corals\Foundation\Formatter\Exceptions\MissingPlaceholderException;


final class Formatter
{
    use Unconstructable;

    /**
     * Format the given pattern by replacing positional and named placeholders
     * with their corresponding argument values. Refer to the library
     * documentation for more information.
     *
     *     Documentation
     * @param $pattern
     *     The pattern that specifies the formatting which should be applied,
     *     see examples for more information.
     * @param $arguments
     *     Arguments (positional and named) to replace in the pattern.
     * @throws \Exception
     *     if any of the values inside `$arguments` throw something while being
     *     converted into a string.
     * @throws  \Corals\Foundation\Formatter\Exceptions\InvalidArgumentException
     *     if a value cannot be converted to a string, this includes all kinds
     *     or resources and classes without any of the following methods:
     *     `__toString`, `toString`, `toInt`, or `toFloat`.
     * @throws  \Corals\Foundation\Formatter\Exceptions\MissingPlaceholderException
     *     if a placeholder in the pattern is missing from the arguments.
     */
    public static function format(string $pattern, array $arguments): string
    {
        if (\strpos($pattern, '[') !== \false) {
            $pattern = \preg_replace_callback(
            /* @lang RegExp */
                '/(?<!\[)\[((?:[^\[\]]|(?R))+)](?!])/',
                static function ($matches) use ($arguments) {
                    if (\preg_match_all(/* @lang RegExp */
                        '@(?<!\{)\{\+?([a-z\d_-]+)(?:}}|[^}])*}\?(?!})@i', $matches[1], $placeholders)) {
                        /** @noinspection ForeachSourceInspection */
                        foreach ($placeholders[1] as $placeholder) {
                            if (empty($arguments[$placeholder])) {
                                return '';
                            }
                        }

                        return $matches[1];
                    }

                    return '';
                },
                $pattern
            );
        }

        $pattern = \preg_replace_callback(
        /* @lang RegExp */
            '@(?<!\{)\{(\+)?([A-Za-z\d_-]*)(?:\.([1-9]\d*))?(?:#([bceopx]))?(?::((?:}}|[^}])+))?}\??(?!})@',
            static function ($matches) use ($arguments) {
                static $counter = 0;

                $placeholder = $matches[2] === '' ? $counter++ : $matches[2];

                if (\array_key_exists($placeholder, $arguments)) {
                    $sign = $matches[1] === '+';
                    $decimals = $matches[3] ?? 0;
                    $format = $matches[4] ?? '';
                    $conjunction = $matches[5] ?? '';

                    return self::formatArg($arguments[$placeholder], $sign, (int)$decimals, $format, $conjunction);
                }

                if (isset($matches[5]) && $matches[5] === '?') {
                    return 'void';
                }

                throw MissingPlaceholderException::new($placeholder, $arguments);
            },
            $pattern
        );

        return \str_replace(['{{', '}}', '[[', ']]'], ['{', '}', '[', ']'], $pattern);
    }

    /** @noinspection MoreThanThreeArgumentsInspection */
    /**
     * @throws \Exception
     * @throws  \Corals\Foundation\Formatter\Exceptions\InvalidArgumentException
     */
    private static function formatArg($arg, bool $sign, int $decimals, string $format, string $conjunction): string
    {
        if ($conjunction === '?') {
            $type = Value::getType($arg);

            if ($type === Value::TYPE_RESOURCE) {
                $type = \get_resource_type($arg) . " {$type}";
            }

            return $type;
        }

        if ($arg === \null) {
            return 'NULL';
        }

        if (\is_string($arg)) {
            return self::formatString(Value::TYPE_STRING, $arg, $format);
        }

        if (\is_array($arg)) {
            return self::formatArrayArg(Value::TYPE_ARRAY, \array_values($arg), $sign, $decimals, $format, $conjunction);
        }

        if (\is_bool($arg)) {
            return $arg ? 'TRUE' : 'FALSE';
        }

        if (\is_float($arg) || \is_int($arg)) {
            return self::formatNumber($arg, $sign, $decimals, $format);
        }

        if (\is_object($arg)) {
            if (\method_exists($arg, 'toString')) {
                return self::formatString(\get_class($arg), $arg->toString(), $format);
            }

            if (\method_exists($arg, 'toInt')) {
                return self::formatNumber($arg->toInt(), $sign, $decimals, $format);
            }

            if (\method_exists($arg, 'toFloat')) {
                return self::formatNumber($arg->toFloat(), $sign, $decimals, $format);
            }

            if (\method_exists($arg, '__toString')) {
                return self::formatString(\get_class($arg), (string)$arg, $format);
            }

            if ($arg instanceof \Traversable) {
                return self::formatArrayArg(\get_class($arg), \iterator_to_array($arg, \false), $sign, $decimals, $format, $conjunction);
            }
        }

        throw InvalidArgumentException::new($arg);
    }

    /** @noinspection MoreThanThreeArgumentsInspection */
    /**
     * @throws \Exception
     * @throws  \Corals\Foundation\Formatter\Exceptions\InvalidArgumentException
     */
    private static function formatArrayArg(string $type, array $arg, bool $sign, int $decimals, string $format, string $conjunction): string
    {
        $c = \count($arg);
        switch ($c) {
            case 0:
                return "empty {$type}";

            case 1:
                return self::formatArg($arg[0], $sign, $decimals, $format, $conjunction);

            case 2:
                $left = self::formatArg($arg[0], $sign, $decimals, $format, $conjunction);
                $right = self::formatArg($arg[1], $sign, $decimals, $format, $conjunction);

                return $conjunction === ''
                    ? "{$left}, {$right}"
                    : "{$left} {$conjunction} {$right}";
        }

        $last = self::formatArg(\array_pop($arg), $sign, $decimals, $format, $conjunction);
        $list = '';
        foreach ($arg as $item) {
            $list .= self::formatArg($item, $sign, $decimals, $format, $conjunction) . ', ';
        }

        return $conjunction === ''
            ? "{$list}{$last}"
            : "{$list}{$conjunction} {$last}";
    }

    /** @noinspection MoreThanThreeArgumentsInspection */
    /** @param float|int $arg */
    private static function formatNumber($arg, bool $sign, int $decimals, string $format): string
    {
        if ($format) {
            if ($format === 'e') {
                $pattern = '%';
                $sign && $pattern .= '+';
                $decimals > 0 && $pattern .= ".{$decimals}";

                return \sprintf("{$pattern}e", $arg);
            }

            $formats = [
                'b' => '0b%b',
                'o' => '0o%o',
                'x' => '0x%X',
            ];

            if (isset($formats[$format])) {
                return \sprintf($formats[$format], $arg);
            }
        }

        return ($sign && $arg >= 0 ? '+' : '') . \number_format($arg, $decimals);
    }

    private static function formatString(string $type, string $arg, string $format): string
    {
        if ($arg === '') {
            return "empty {$type}";
        }

        if ($format) {
            if ($format === 'c') {
                return self::formatControlChars($arg, '^?', static function (int $ord): string {
                    return '^' . \chr($ord + 64);
                });
            }

            if ($format === 'p') {
                return self::formatControlChars($arg, '␡', static function (int $ord): string {
                    return \html_entity_decode('&#' . ($ord + 9216) . ';', 0, 'UTF-8');
                });
            }
        }

        return $arg;
    }

    private static function formatControlChars(string $string, string $del, callable $cc): string
    {
        $result = '';

        for ($i = 0, $bytes = \strlen($string); $i < $bytes; ++$i) {
            $ord = \ord($string[$i]);

            if (0 <= $ord && $ord <= 31) {
                $result .= $cc($ord);
            } elseif ($ord === 127) {
                $result .= $del;
            } else {
                $result .= $string[$i];
            }
        }

        return $result;
    }
}
