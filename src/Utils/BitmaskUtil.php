<?php

namespace App\Utils;

class BitmaskUtil
{
    public static function containsOneOfValues(int $bitmask, array $values): bool
    {
        foreach ($values as $value) {
            if (self::containValue($bitmask, $value)) {
                return true;
            }
        }

        return false;
    }


    public static function containValue(int $bitmask, int $value): bool
    {
        return ($bitmask & $value) > 0;
    }
}