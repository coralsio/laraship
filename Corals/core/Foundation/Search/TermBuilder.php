<?php

namespace Corals\Foundation\Search;

class TermBuilder
{

    public static function terms($search, $config, $class = null)
    {
        if ($class) {
            $model = new $class;
        } else {
            $model = null;
        }

        $search = self::textCleanUp($search, $model->replaceSpecialChar);

        $wildcards = $config['enable_wildcards'] ?? true;

        $terms = collect(preg_split('/[\s,]+/', $search));

        if ($wildcards === true || $wildcards === 'true') {
            $terms = $terms->reject(function ($part) use ($model) {
                if ($model) {
                    $part = $model->getTermMapping($part);
                }

                return empty(trim($part)) || mb_strlen($part) < 3;
            })->map(function ($part) use ($model) {
                if ($model) {
                    $part = $model->getTermMapping($part);
                }
                return '*' . $part . '*';
            });
        }

        return $terms;
    }

    /**
     * @param $text
     * @return array|string|string[]
     */
    public static function textCleanUp($text, $replaceSpecialChar = true)
    {
        if (!$replaceSpecialChar) {
            return trim($text);
        }

        $text = trim(preg_replace('/[\/+\-><()~*\"@.]+/', 'X', $text));

        $text = str_replace(['آ', 'أ', 'ة'], ['ا', 'ا', 'ه'], $text);

        return strtolower($text);
    }
}
