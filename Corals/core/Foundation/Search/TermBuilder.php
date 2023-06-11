<?php

namespace Corals\Foundation\Search;

class TermBuilder
{

    public static function terms($search, $config)
    {
        $search = self::textCleanUp($search);

        $wildcards = $config['enable_wildcards'] ?? true;

        $terms = collect(preg_split('/[\s,]+/', $search));

        if ($wildcards === true || $wildcards === 'true') {
            $terms = $terms->reject(function ($part) {
                return empty(trim($part));
            })->map(function ($part) {
                return $part . '*';
            });
        }
        return $terms;
    }

    /**
     * @param $text
     * @return array|string|string[]
     */
    public static function textCleanUp($text)
    {
        $text = trim(preg_replace('/[\/+\-><()~*\"@.]+/', 'X', $text));

        $text = str_replace(['أ', 'ة'], ['ا', 'ه'], $text);

        return $text;
    }
}
