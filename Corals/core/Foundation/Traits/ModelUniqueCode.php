<?php

namespace Corals\Foundation\Traits;

trait ModelUniqueCode
{
    /**
     * @param $codePrefix
     * @param string $codeColumn
     * @param int $codeLength
     * @param bool $isSequential
     * @return string
     */
    public static function getSeqCode($codePrefix = null, $codeColumn = 'code', $codeLength = 6)
    {
        return self::getCode($codePrefix, $codeColumn, true, $codeLength);
    }

    /**
     * @param $codePrefix
     * @param string $codeColumn
     * @param bool $isSequential
     * @param int $codeLength
     * @return string
     */
    public static function getCode($codePrefix = null, $codeColumn = 'code', $isSequential = true, $codeLength = 6)
    {
        if (is_null($codePrefix) && property_exists(self::class, 'codePrefix')) {
            $codePrefix = self::$codePrefix;
        }
        if ($isSequential) {
            // Get the last created order
            $number = self::query()->max('id');

            // We get here if there is no records at all
            // If there is no number set it to 0, which will be 1 at the end.
            if (is_null($number)) {
                $number = 0;
            }

            do {
                $number++;

                $code = trim($codePrefix . '-' . sprintf('%0' . $codeLength . 'd', $number), '-');

                // Add the string in front and higher up the number.
                // the %06d part makes sure that there are always 6 numbers in the string.
                // so it adds the missing zero's when needed.
                $recordExists = self::query()->where($codeColumn, $code)->first();
            } while ($recordExists);

            return $code;
        } else {
            //RandomCode
            while (true) {
                $code = randomCode($codePrefix, $codeLength);
                if (!self::query()->where($codeColumn, $code)->first()) {
                    return $code;
                    break;
                }
            }
        }
    }

//    public function getCodeAttribute()
//    {
//        if (empty($this->attributes['code'])) {
//            return self::getSeqCode();
//        } else {
//            return $this->attributes['code'];
//        }
//    }

    public static function findByCode($code, $codeColumn = 'code')
    {
        return self::where($codeColumn, $code)->first();
    }
}
