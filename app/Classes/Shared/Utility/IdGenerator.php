<?php

namespace App\Classes\Shared\Utility;

class IdGenerator
{
    private static int $value = 1;

    public static function get(): int
    {
        $val = self::$value;
        self::$value++;
        return $val;
    }
}